import re
import mysql.connector
from collections import namedtuple
import csv
import json

# Function to clean the tweet
def clean_url(twitter_result):
    return re.sub(r'http\S+', '', twitter_result.lower())

def clean_twitter_sym(twitter_result):
    return re.sub(r"(@[A-Za-z0-9]+)|(#[A-Za-z0-9]+)|(r[^\x00-\x7F]+)|([^0-9A-Za-z])", ' ', clean_url(twitter_result))

def clean_digit_sym(twitter_result):
    return re.sub(r"(:)|(r‚Ä¶)|(rt|RT)|([0-9])", '', clean_twitter_sym(twitter_result))

def clean_tags(twitter_result):
    return re.sub(r"&lt;/?.*?&gt;", "&lt;&gt;", clean_digit_sym(twitter_result))

def clean_tweet(twitter_result):
    return re.sub(r"( +)", ' ', clean_digit_sym(twitter_result).lstrip(' '))

# Function to get data from MySQL
def get_data_from_mysql(host, user, password, database):
    connection = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        database=database
    )
    cursor = connection.cursor(dictionary=True)
    cursor.execute("SELECT id, full_text FROM datasets")
    result = cursor.fetchall()
    cursor.close()
    connection.close()
    return result

# Function to get existing datasets_id from pre_processings table
def get_existing_ids(host, user, password, database):
    connection = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        database=database
    )
    cursor = connection.cursor()
    cursor.execute("SELECT datasets_id FROM pre_processings")
    result = cursor.fetchall()
    existing_ids = {row[0] for row in result}
    cursor.close()
    connection.close()
    return existing_ids

# Function to save cleaned data to MySQL
def save_cleaned_data_to_mysql(host, user, password, database, cleaned_data):
    connection = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        database=database
    )
    cursor = connection.cursor()
    query = "INSERT INTO pre_processings (datasets_id, original, cleaned, case_folded, tokenized) VALUES (%s, %s, %s, %s, %s)"
    cursor.executemany(query, cleaned_data)
    connection.commit()
    cursor.close()
    connection.close()

# Main function to process tweets
# Function to process tweets from CSV and save cleaned data to JSON
def process_tweets(input_csv, output_json):
    cleaned_data = []

    with open(input_csv, 'r', newline='', encoding='utf-8') as csv_file:
        reader = csv.reader(csv_file)
        for row in reader:
            cleaned_text = clean_tweet(row[1])
            cleaned_data.append({'id': row[0], 'cleaned_text': cleaned_text})

    # Save cleaned data to JSON file
    with open(output_json, 'w', encoding='utf-8') as json_file:
        json.dump(cleaned_data, json_file, ensure_ascii=False, indent=4)

if __name__ == "__main__":
    import argparse
    import time
    print ("Start")
    parser = argparse.ArgumentParser(description='Process tweets and clean them.')
    parser.add_argument('--input_csv', type=str, required=True, help='Input CSV file containing tweet data')
    parser.add_argument('--output_json', type=str, required=True, help='Output JSON file to store cleaned data')

    args = parser.parse_args()

    start_time = time.perf_counter()
    process_tweets(args.input_csv, args.output_json)
    end_time = time.perf_counter()

    print(f"Process Sudah Selesai, Silahkan Restart Browser")
    print(f"Waktu Eksekusi: {end_time - start_time} detik")
