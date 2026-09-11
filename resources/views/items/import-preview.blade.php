<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Import KIBB - SIPBAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Preview Import KIBB</h1>
                        <p class="text-gray-600 mt-1">Review data sebelum konfirmasi import</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('items.import.cancel') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="grid grid-cols-4 gap-4 mt-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ count($previewData) }}</div>
                        <div class="text-sm text-blue-700">Total Data</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-600">{{ count(array_filter($previewData, fn($item) => !$item['needs_review'])) }}</div>
                        <div class="text-sm text-green-700">Siap Import</div>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-yellow-600">{{ count(array_filter($previewData, fn($item) => $item['needs_review'])) }}</div>
                        <div class="text-sm text-yellow-700">Perlu Review</div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-red-600">{{ count($skipped) }}</div>
                        <div class="text-sm text-red-700">Dilewati</div>
                    </div>
                </div>
            </div>

            <!-- Errors Section -->
            @if(count($errors) > 0)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <h3 class="font-semibold text-red-800 mb-2">Error ({{ count($errors) }} baris)</h3>
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach($errors as $error)
                    <li>Baris {{ $error['row'] }}: {{ $error['reason'] }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Skipped Section -->
            @if(count($skipped) > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <h3 class="font-semibold text-yellow-800 mb-2">Dilewati ({{ count($skipped) }} baris)</h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    @foreach($skipped as $skipped)
                    <li>Baris {{ $skipped['row'] }} (Kode: {{ $skipped['kode_kibb'] }}): {{ $skipped['reason'] }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Preview Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Baris</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode KIBB</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No. Reg</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Barang</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Merk</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Lokasi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tahun</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($previewData as $index => $item)
                            <tr class="{{ $item['needs_review'] ? 'bg-yellow-50' : '' }}">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['row'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['kode_kibb'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['nomor_reg'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $item['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item['merk'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item['tipe'] ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <select name="items[{{ $index }}][category_id]" class="text-sm border rounded px-2 py-1 {{ $item['needs_review'] ? 'border-yellow-400 bg-yellow-50' : 'border-gray-300' }}">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $item['category_id'] == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="items[{{ $index }}][location_id]" class="text-sm border rounded px-2 py-1 {{ $item['needs_review'] ? 'border-yellow-400 bg-yellow-50' : 'border-gray-300' }}">
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ $item['location_id'] == $location->id ? 'selected' : '' }}>
                                            {{ $location->building }} {{ $location->floor ? '- ' . $location->floor : '' }} {{ $location->room ? '- ' . $location->room : '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item['purchase_year'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if($item['needs_review'])
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Perlu Review
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Siap
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Confirm Button -->
            <div class="mt-6 flex justify-end">
                <form method="POST" action="{{ route('items.import.confirm') }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Konfirmasi Import ({{ count($previewData) }} barang)
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
