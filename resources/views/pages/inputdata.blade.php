@extends('layouts.dasboard-layout')

@section('maincontent')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<!-- Main Content Area -->
<main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-6xl mx-auto">
            <!-- Tabs Navigation -->
            <div class="flex border-b"> 
                <button id="manualTab" class="tab-button flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600">
                    <i class="fas fa-keyboard mr-2"></i>Manual Input
                </button>
                <button id="excelTab" class="tab-button active flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700">
                    <i class="fas fa-file-excel mr-2"></i>Import Excel
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="p-8">
                <!-- Excel Import Tab -->
                <div id="excelContent" class="tab-content hidden">
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-upload mr-2"></i>Upload Excel File
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="file-upload" type="file" class="sr-only" accept=".xlsx, .xls">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">XLSX or XLS up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            <span id="fileName" class="text-sm font-medium text-gray-700 hidden"></span>
                        </div>
                        <button id="processExcelBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-cog mr-2"></i>Process Data
                        </button>
                    </div>
                </div>

                <!-- Manual Input Tab -->
                <div id="manualContent" class="tab-content ">
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Add New Record</h3>
                            <button id="addRowBtn" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition duration-200">
                                <i class="fas fa-plus mr-1"></i>Add Row
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="manualInputTable" class="bg-white divide-y divide-gray-200">
                                    <!-- Rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
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
    </div>

    <script>
        // Global variables
        let processedData = [];
        let currentTab = 'manual';
        
        // DOM elements
        const excelTab = document.getElementById('excelTab');
        const manualTab = document.getElementById('manualTab');
        const excelContent = document.getElementById('excelContent');
        const manualContent = document.getElementById('manualContent');
        const fileUpload = document.getElementById('file-upload');
        const fileName = document.getElementById('fileName');
        const processExcelBtn = document.getElementById('processExcelBtn');
        const addRowBtn = document.getElementById('addRowBtn');
        const manualInputTable = document.getElementById('manualInputTable');
        const resultsSection = document.getElementById('resultsSection');
        const downloadJsonBtn = document.getElementById('downloadJsonBtn');
        const previewBtn = document.getElementById('previewBtn');
        const recordCount = document.getElementById('recordCount');
        const statusMessage = document.getElementById('statusMessage');
        const previewContainer = document.getElementById('previewContainer');
        const previewHeaders = document.getElementById('previewHeaders');
        const previewData = document.getElementById('previewData');
        const previewNote = document.getElementById('previewNote');

        // Initialize the app
        document.addEventListener('DOMContentLoaded', () => {
            // Tab switching
            manualTab.addEventListener('click', () => switchTab('manual'));
            excelTab.addEventListener('click', () => switchTab('excel'));
            
            // Excel file handling
            fileUpload.addEventListener('change', handleFileSelect);
            processExcelBtn.addEventListener('click', processExcelData);
            
            // Manual input handling
            addRowBtn.addEventListener('click', addManualRow);
            
            // Results handling
            downloadJsonBtn.addEventListener('click', downloadJSON);
            previewBtn.addEventListener('click', togglePreview);
            
            // Add initial row for manual input
            addManualRow();
        });

        function switchTab(tab) {
            currentTab = tab;
            
            // Update tab buttons
            if (tab === 'excel') {
                excelTab.classList.add('active', 'text-blue-600', 'border-blue-600', 'border-b-2');
                excelTab.classList.remove('text-gray-500', 'hover:text-gray-700');
                manualTab.classList.remove('active', 'text-blue-600', 'border-blue-600');
                manualTab.classList.add('text-gray-500', 'hover:text-gray-700');
            } else {
                manualTab.classList.add('active', 'text-blue-600', 'border-blue-600');
                manualTab.classList.remove('text-gray-500', 'hover:text-gray-700');
                excelTab.classList.remove('active', 'text-blue-600', 'border-blue-600');
                excelTab.classList.add('text-gray-500', 'hover:text-gray-700');
            }
            
            // Update content visibility
            excelContent.classList.toggle('hidden', tab !== 'excel');
            manualContent.classList.toggle('hidden', tab !== 'manual');
        }

        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileName.classList.remove('hidden');
                processExcelBtn.disabled = false;
            } else {
                fileName.classList.add('hidden');
                processExcelBtn.disabled = true;
            }
        }

        function processExcelData() {
            const file = fileUpload.files[0];
            if (!file) {
                showStatus('Please select an Excel file first.', 'error');
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    
                    // Convert to JSON
                    let jsonData = XLSX.utils.sheet_to_json(worksheet);
                    
                    // Process the data
                    processedData = jsonData.map(row => {
                        // Determine gender
                        let jenisKelamin = null;
                        if (row['Laki'] !== undefined && row['Laki'] !== null && row['Laki'] !== '') {
                            jenisKelamin = 'L';
                        } else if (row['Perempuan'] !== undefined && row['Perempuan'] !== null && row['Perempuan'] !== '') {
                            jenisKelamin = 'P';
                        }
                        
                        // Create new object with transformed data
                        return {
                            'Nama Lengkap': row['Nama Lengkap'] || null,
                            'No KK': row['No KK'] || null,
                            'NIK': row['NIK'] || null,
                            'No pil': row['No pil'] || null,
                            'Banjar': row['Banjar'] || null,
                            'Urut': row['Urut'] || null,
                            'NPK': row['NPK'] || null,
                            'Nika': row['Nika'] || null,
                            'Tempat Lahir': row['Tempat Lahir'] || null,
                            'Tanggal Lahir': formatDate(row['Tangal lahir'] || row['Tanggal Lahir'] || null),
                            'Agama': row['Agama'] || null,
                            'Pendidikan': row['Pendidikan'] || null,
                            'Pekerjaan': row['Pekerjaan'] || null,
                            'Alamat': row['Alamat'] || null,
                            'Status Kawin': row['Status Kawin'] || null,
                            'Tanggal Kawin': formatDate(row['Tanggal Kawin'] || null),
                            'Status Keluarga': row['Status Keluarga'] || null,
                            'Kewarganegaraan': row['Kewarganegaraan'] || null,
                            'Jenis Kelamin': jenisKelamin
                        };
                    });
                    
                    showResults();
                    showStatus(`Successfully processed ${processedData.length} records from Excel file.`, 'success');
                    
                } catch (error) {
                    console.error(error);
                    showStatus(`Error processing file: ${error.message}`, 'error');
                }
            };

            reader.onerror = function() {
                showStatus("Error reading the file.", 'error');
            };

            reader.readAsArrayBuffer(file);
        }

        function addManualRow() {
            const rowId = Date.now();
            const row = document.createElement('tr');
            row.id = `row-${rowId}`;
            row.className = 'manual-row';
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" class="nama-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Nama Lengkap">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <select class="gender-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="date" class="dob-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" class="delete-row text-red-600 hover:text-red-900" data-rowid="${rowId}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            manualInputTable.appendChild(row);
            
            // Add event listener to delete button
            row.querySelector('.delete-row').addEventListener('click', function() {
                if (manualInputTable.querySelectorAll('.manual-row').length > 1) {
                    row.remove();
                } else {
                    // Clear inputs instead of removing the last row
                    row.querySelector('.nama-input').value = '';
                    row.querySelector('.gender-select').value = '';
                    row.querySelector('.dob-input').value = '';
                }
            });
        }

        function processManualData() {
            const rows = manualInputTable.querySelectorAll('.manual-row');
            processedData = [];
            
            rows.forEach(row => {
                const nama = row.querySelector('.nama-input').value.trim();
                const gender = row.querySelector('.gender-select').value;
                const dob = row.querySelector('.dob-input').value;
                
                if (nama || gender || dob) {
                    processedData.push({
                        'Nama Lengkap': nama || null,
                        'Jenis Kelamin': gender || null,
                        'Tanggal Lahir': dob ? formatDate(dob) : null,
                        // Add other fields with null values
                        'No KK': null,
                        'NIK': null,
                        'No pil': null,
                        'Banjar': null,
                        'Urut': null,
                        'NPK': null,
                        'Nika': null,
                        'Tempat Lahir': null,
                        'Agama': null,
                        'Pendidikan': null,
                        'Pekerjaan': null,
                        'Alamat': null,
                        'Status Kawin': null,
                        'Tanggal Kawin': null,
                        'Status Keluarga': null,
                        'Kewarganegaraan': null
                    });
                }
            });
            
            if (processedData.length > 0) {
                showResults();
                showStatus(`Successfully processed ${processedData.length} manual entries.`, 'success');
            } else {
                showStatus('No valid data entered.', 'warning');
            }
        }

        function showResults() {
            resultsSection.classList.remove('hidden');
            recordCount.textContent = `${processedData.length} records processed`;
            previewContainer.classList.add('hidden');
        }

        function togglePreview() {
            if (previewContainer.classList.contains('hidden')) {
                renderPreview();
                previewContainer.classList.remove('hidden');
                previewBtn.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Hide Preview';
            } else {
                previewContainer.classList.add('hidden');
                previewBtn.innerHTML = '<i class="fas fa-eye mr-2"></i>Preview Data';
            }
        }

        function renderPreview() {
            // Clear previous preview
            previewHeaders.innerHTML = '';
            previewData.innerHTML = '';
            
            if (processedData.length === 0) {
                previewNote.textContent = 'No data to preview.';
                return;
            }
            
            // Create headers from the first item's keys
            const firstItem = processedData[0];
            Object.keys(firstItem).forEach(key => {
                const th = document.createElement('th');
                th.className = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
                th.textContent = key;
                previewHeaders.appendChild(th);
            });
            
            // Show up to 10 rows in preview
            const previewRows = processedData.slice(0, 10);
            previewRows.forEach(item => {
                const tr = document.createElement('tr');
                
                Object.values(item).forEach(value => {
                    const td = document.createElement('td');
                    td.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
                    td.textContent = value !== null ? value : '';
                    tr.appendChild(td);
                });
                
                previewData.appendChild(tr);
            });
            
            if (processedData.length > 10) {
                previewNote.textContent = `Showing 10 of ${processedData.length} records.`;
            } else {
                previewNote.textContent = `Showing all ${processedData.length} records.`;
            }
        }

        function downloadJSON() {
            if (!processedData.length) {
                showStatus('No data to download.', 'warning');
                return;
            }
            
            const jsonStr = JSON.stringify(processedData, null, 4);
            const blob = new Blob([jsonStr], { type: 'application/json' });
            saveAs(blob, 'processed_data.json');
            
            showStatus('JSON file downloaded successfully.', 'success');
        }

        function formatDate(dateValue) {
            if (!dateValue) return null;
            
            // If it's already a string in the correct format
            if (typeof dateValue === 'string' && dateValue.match(/^\d{2}-\d{2}-\d{4}$/)) {
                return dateValue;
            }
            
            // If it's a date input value (YYYY-MM-DD)
            if (typeof dateValue === 'string' && dateValue.match(/^\d{4}-\d{2}-\d{2}$/)) {
                const parts = dateValue.split('-');
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            
            // If it's a number (Excel timestamp)
            if (typeof dateValue === 'number') {
                const excelEpoch = new Date(1899, 11, 30);
                const jsDate = new Date(excelEpoch.getTime() + dateValue * 24 * 60 * 60 * 1000);
                
                const day = String(jsDate.getDate()).padStart(2, '0');
                const month = String(jsDate.getMonth() + 1).padStart(2, '0');
                const year = jsDate.getFullYear();
                
                return `${day}-${month}-${year}`;
            }
            
            // If it's a Date object
            if (dateValue instanceof Date) {
                const day = String(dateValue.getDate()).padStart(2, '0');
                const month = String(dateValue.getMonth() + 1).padStart(2, '0');
                const year = dateValue.getFullYear();
                
                return `${day}-${month}-${year}`;
            }
            
            return null;
        }

        function showStatus(message, type) {
            statusMessage.textContent = message;
            statusMessage.className = 'mb-4 p-3 rounded-md';
            
            switch (type) {
                case 'success':
                    statusMessage.classList.add('bg-green-50', 'text-green-800');
                    break;
                case 'error':
                    statusMessage.classList.add('bg-red-50', 'text-red-800');
                    break;
                case 'warning':
                    statusMessage.classList.add('bg-yellow-50', 'text-yellow-800');
                    break;
                default:
                    statusMessage.classList.add('bg-blue-50', 'text-blue-800');
            }
            
            statusMessage.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                statusMessage.classList.add('hidden');
            }, 5000);
        }
    </script>
@endsection