<div class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
    <!-- Card Header -->
    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-white/5">
        <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            Confusion Matrix TF-IDF
        </h2>
    </div>

    <div class="fi-ta-content divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10 !border-t-0">
        <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
            <thead class="divide-y divide-gray-200 dark:divide-white/5">
            <tr class="bg-gray-50 dark:bg-white/5">
                <th class="fi-ta-header-cell px-3 py-3.5">Confusion</th>
                <th class="fi-ta-header-cell px-3 py-3.5">Nilai</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
            @foreach ($metrics as $metric)
                <tr>
                    <td class="fi-ta-cell px-3 py-4">{{ $metric['metric'] }}</td>
                    <td class="fi-ta-cell px-3 py-4">{{ round($metric['value']) }}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
