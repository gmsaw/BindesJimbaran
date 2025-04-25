<div id="resultsSection" class="bg-white rounded-xl shadow-lg overflow-hidden max-w-6xl mx-auto mt-8 hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Processed Data</h2>
            </div>
            <div class="p-6">
                <div class="flex justify-between mb-6">
                    <div class="flex space-x-3">
                        <button id="downloadJsonBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-file-export mr-2"></i>Tambahkan ke Database
                        </button>
                        <button id="previewBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-200">
                            <i class="fas fa-eye mr-2"></i>Preview Data
                        </button>
                    </div>
                    <div id="recordCount" class="text-sm text-gray-500 self-center"></div>
                </div>

                <div id="statusMessage" class="mb-4 p-3 rounded-md hidden"></div>

                <div id="previewContainer" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr id="previewHeaders"></tr>
                            </thead>
                            <tbody id="previewData" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-sm text-gray-500 text-center" id="previewNote"></div>
                </div>
            </div>
        </div>