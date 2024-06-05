import os
os.environ['APPDATA'] = r"C:\Users\dwise\AppData\Roaming"
os.environ["USERPROFILE"] = r"C:\Users\dwise"

import pandas
import csv
# import seaborn
import time
# import matplotlib.pyplot as plt
from itertools import filterfalse, islice
from functools import reduce, lru_cache
import operator
from nltk.corpus import stopwords
import concurrent.futures
from wordcloud import WordCloud
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
import json
# sentimentDataset = pandas.read_csv('data/datasetAnalysis/lexicon-word-dataset.csv')
# kataPenguatFile = pandas.read_csv("data/datasetAnalysis/kata-keterangan-penguat.csv")

sentimentDatasetFile = None
kataPenguatFile = None
tweetDatasetFile = None
outDatasetFile = None

negasi = ["tidak", "tidaklah", "bukan", "bukanlah", "bukannya","ngga", "nggak", "enggak", "nggaknya",
    "kagak", "gak"]
stemmer = StemmerFactory().create_stemmer()

preprocessingTweet = lambda wordTweets : filterfalse(lambda x:
    True if (x in stopwords.words('indonesian')
        and x not in (x for x in kataPenguatFile['words'])
            and x not in negasi)
        else False, wordTweets.split()) # -> itertools.filterfalse()

# @lru_cache(maxsize=2180)
def findWeightSentiment(wordTweet:str) -> int:
    global  sentimentDataset
    for x, i in enumerate(x for x in sentimentDataset['word']):
        if i == wordTweet:
            return next(islice((x for x in sentimentDataset['weight']), x, None))
    return 0

# @lru_cache(maxsize=30)
def findWeightInf(wordTweet:str) -> float:
    global kataPenguatFile
    for x, i in enumerate(x for x in kataPenguatFile['words']):
        if i == wordTweet:
            return next(islice((x for x in kataPenguatFile['weight']), x, None))
    return 0

def wordTweetsFinder(wordTweets:str, preprocessingFunc) -> list:
    cleanText = stemmer.stem(" ".join([x for x in preprocessingFunc(wordTweets)]))

    wordTweets = [x for x in cleanText.split()]

    return wordTweets

def sentimentFinder(wordTweets:str, preprocessingFunc) -> list:
    global kataPenguatFile
    cleanText = stemmer.stem(" ".join([x for x in preprocessingFunc(wordTweets)]))
    sentimentWeightList = []
    sentimentInfList = []
    wordTweets = [x for x in cleanText.split()]
    for x in wordTweets:
        if (wordTweets[wordTweets.index(x) - 1]) in negasi:
            sentimentWeightList.append(-1*findWeightSentiment(x))
        elif x in (x for x in kataPenguatFile['words']):
            sentimentInfList.append(findWeightInf(x))
        else:
            sentimentWeightList.append(findWeightSentiment(x))
    return sentimentWeightList, sentimentInfList

def sentimentCalc(args) -> float:
    sentimentWeight = list(args[0])
    sentimentInf = list(args[1])
    if len(sentimentWeight) >= 1 and len(sentimentInf) == 0:
        return sum(sentimentWeight)
    elif len(sentimentWeight) >= 1 and len(sentimentInf) >= 1:
        return reduce(operator.mul, list(map(lambda x : x + 1.0, sentimentInf))) * sum(sentimentWeight)
    else:
        return 0

sentimentProcess = lambda dataset : (dict(original_tweet=x, sentiment_result=sentimentCalc(sentimentFinder(x, preprocessingTweet)), token = wordTweetsFinder(x, preprocessingTweet))
    for x in dataset)

sentimentTokenProcess = lambda dataset : (dict(id=x["id"], cleaned=x["cleaned"], token = wordTweetsFinder(x["cleaned"], preprocessingTweet))
                                          for _, x in dataset.iterrows())


def sentimentCSV() -> csv:
    global tweetDatasetFile, outDatasetFile
    tweetDataset = pandas.read_csv(tweetDatasetFile)
    tweetDataset = tweetDataset.drop_duplicates(subset=['cleaned'])
    tweetDataset = tweetDataset.reset_index(drop=True)

    with open(outDatasetFile,'w') as file:
        writer = csv.DictWriter(file, ["original_tweet", "sentiment_result", "token"])
        writer.writeheader()
        with concurrent.futures.ThreadPoolExecutor() as executor:
            executor.map(writer.writerow, sentimentProcess(tweetDataset['tweet']))

    # Save cleaned data to JSON file
    # Create a dictionary
    jsonArray = []

    # read csv file
    with open(outDatasetFile, encoding='utf-8') as csvf:
        # load csv file data using csv library's dictionary reader
        csvReader = csv.DictReader(csvf)

        # convert each csv row into python dict
        for row in csvReader:
            # add this python dict to json array
            jsonArray.append(row)

        # Open a json writer, and use the json.dumps() function
        # to dump data
    with open(outDatasetFile.replace('.csv', '.json'), 'w', encoding='utf-8') as jsonf:
        jsonString = json.dumps(jsonArray, indent=4)
        jsonf.write(jsonString)

def sentimentToken() -> csv:
    global tweetDatasetFile, outDatasetFile
    tweetDataset = pandas.read_csv(tweetDatasetFile,  header=None)
    tweetDataset.columns = ["id", "cleaned"]
    #  print(tweetDataset)
    # tweetDataset = tweetDataset.drop_duplicates(subset=['tweet'])
    # tweetDataset = tweetDataset.reset_index(drop=True)

    with open(outDatasetFile, 'w') as file:
        writer = csv.DictWriter(file, ["id", "cleaned", "token"])
        writer.writeheader()
        with concurrent.futures.ThreadPoolExecutor() as executor:

            executor.map(writer.writerow, sentimentTokenProcess(tweetDataset))

    # Save cleaned data to JSON file
    # Create a dictionary
    jsonArray = []

    # read csv file
    with open(outDatasetFile, encoding='utf-8') as csvf:
        # load csv file data using csv library's dictionary reader
        csvReader = csv.DictReader(csvf)

        # convert each csv row into python dict
        for row in csvReader:
            # add this python dict to json array
            jsonArray.append(row)

        # Open a json writer, and use the json.dumps() function
        # to dump data
    with open(outDatasetFile.replace('.csv', '.json'), 'w', encoding='utf-8') as jsonf:
        jsonString = json.dumps(jsonArray, indent=4)
        jsonf.write(jsonString)


if __name__ == "__main__":

    import argparse
    import time

    parser = argparse.ArgumentParser(description='Process tweets and clean them.')
    parser.add_argument('--sentimentDataset', type=str, required=True)
    parser.add_argument('--kataPenguat', type=str, required=True)
    parser.add_argument('--tweetDataset', type=str, required=True )
    parser.add_argument('--outDataset', type=str, required=True)

    args = parser.parse_args()

    sentimentDatasetFile = pandas.read_csv(args.sentimentDataset)
    sentimentDatasetFile.columns = ["words", "weight"]

    kataPenguatFile = pandas.read_csv(args.kataPenguat)
    kataPenguatFile.columns = ["words", "weight"]

    tweetDatasetFile = args.tweetDataset
    outDatasetFile = args.outDataset

    # nama file untuk hasil sentiment analysis
    time1 = time.perf_counter()
    sentimentToken()
    time2 = time.perf_counter()
    print(f"Process Sudah Selesai, Silahkan Restart Browser")
    print(f"Waktu Eksekusi: {time2 - time1} detik")
#     print(findWeightSentiment.cache_info())
