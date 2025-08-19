<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kas Pro - Transaksi Penjualan</title>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- DataTables CSS & JS (optional) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease-in-out;
        }
        .modal-bg {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: #fff;
            border-radius: 0.75rem;
            padding: 2rem;
            min-width: 300px;
            max-width: 90vw;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .loader-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #6366f1;
            border-radius: 50%;
            width: 3rem; height: 3rem;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);} 
            100% { transform: rotate(360deg);} 
        }
        /* FIX: jangan override .hidden Tailwind */
        .is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ showLockModal: false }">
    <!-- Loading Overlay -->
    <div id="loader-overlay" class="loader-overlay">
        <div class="loader" aria-label="Memuat..."></div>
    </div>

    <!-- Header Section -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">KAS PRO</h1>
                    <p class="text-sm opacity-90">Sistem Kasir Kas</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? '-' }}</p>
                    <p class="text-xs opacity-75">{{ Auth::user()->role ?? '-' }}</p>
                    <p class="text-xs opacity-75">{{ Auth::user()->email ?? '-' }}</p>
                    <div x-data="clock()" x-init="init()" class="text-xs opacity-75 mt-1">
                        <span x-text="currentTime" class="font-mono"></span>
                    </div>

                    <button @click="
                        fetch('{{ route('logout') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(() => showLockModal = true);
                    " class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 11c.5304 0 1.0391.2107 1.4142.5858C13.7893 11.9609 14 12.4696 14 13v3H10v-3c0-.5304.2107-1.0391.5858-1.4142C10.9609 11.2107 11.4696 11 12 11z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M17 8V6a5 5 0 00-10 0v2" />
                            <rect width="14" height="10" x="5" y="11" rx="2" ry="2" />
                        </svg>
                        Lock
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Product Selection Card -->
        <div class="bg-white rounded-xl shadow-sm mb-12">
            <div class="p-3">
                <div class="grid grid-cols-[3fr_1fr_1fr] gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                        <div x-data="productSearch()" class="relative">
                            <input 
                                type="text" 
                                x-model="searchTerm" 
                                @input="filterProducts()" 
                                @focus="showDropdown = true"
                                @click.away="showDropdown = false"
                                placeholder="Cari produk..." 
                                class="w-full px-6 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            >
                            <div x-show="showDropdown && filteredProducts.length > 0" 
                                 class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="product in filteredProducts" :key="product.produk">
                                    <div @click="selectProduct(product)" 
                                         class="px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                        <span x-text="product.produk + ' - ' + formatRupiah(product.harga_jual)"></span>
                                        <span class="text-xs text-gray-500 ml-2" x-text="'Stok: ' + product.stok"></span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="showDropdown && filteredProducts.length === 0 && searchTerm" 
                                 class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg p-4 text-center text-gray-500">
                                Produk tidak ditemukan
                            </div>
                        </div>
                        <input type="hidden" id="produkSelect" x-model="selectedProduct">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" id="jumlahInput" max="999" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" min="1" placeholder="Jumlah max 999">
                    </div>
                    <div class="flex items-end">
                        <button id="tambahBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-3 rounded-lg transition-all duration-200 hover-scale flex items-center justify-center">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Details Card -->
        <div class="bg-white rounded-xl shadow-lg card-shadow">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" id="transaksiTable">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>

                <!-- Summary Section -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-blue-600">Total Penjualan</p>
                            <p class="text-2xl font-bold text-blue-800" id="totalRupiah">Rp 0</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-green-600">Jumlah Item</p>
                            <p class="text-2xl font-bold text-green-800" id="jumlahItem">0</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-yellow-600">Perkiraan Laba</p>
                            <p class="text-2xl font-bold text-yellow-800" id="labaRupiah">Rp 0</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pembayaran (Rp)</label>
                            <input type="number" id="pembayaranInput" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" min="0" placeholder="Masukkan jumlah pembayaran">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kembalian</label>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <p class="text-2xl font-bold text-green-600" id="kembalianText">Rp 0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="resetTransaksi()" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200">
                        Reset
                    </button>
                    <!-- FIX: kirim elemen tombol sebagai argumen -->
                    <button type="button" onclick="submitTransaksi(this)" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 hover-scale flex items-center">
                        <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Lock/Login -->
    <div x-show="showLockModal" class="modal-bg" style="display: none;">
        <div class="modal-content">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Login Kembali</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="flex justify-end">
                  <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const loaderOverlay = document.getElementById('loader-overlay');
            // FIX: gunakan .is-hidden (bukan .hidden Tailwind)
            loaderOverlay.classList.add('is-hidden');
        });

        // Initialize Lucide icons
        lucide.createIcons();

        // Clock component for animated time display
        function clock() {
            return {
                currentTime: '',
                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },
                updateTime() {
                    const now = new Date();
                    const day = now.getDate().toString().padStart(2, '0');
                    const month = (now.getMonth() + 1).toString().padStart(2, '0');
                    const year = now.getFullYear();
                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    this.currentTime = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
                }
            }
        }

        // Product search component
        function productSearch() {
            return {
                searchTerm: '',
                showDropdown: false,
                selectedProduct: '',
                products: @json($productions),
                filteredProducts: [],
                
                init() {
                    this.filteredProducts = this.products;
                },
                
                filterProducts() {
                    if (this.searchTerm === '') {
                        this.filteredProducts = this.products;
                    } else {
                        this.filteredProducts = this.products.filter(product => 
                            product.produk.toLowerCase().includes(this.searchTerm.toLowerCase())
                        );
                    }
                },
                
                selectProduct(product) {
                    // FIX: hilangkan 'Rp ' dobel
                    this.searchTerm = product.produk + ' - ' + formatRupiah(product.harga_jual);
                    this.selectedProduct = product.produk;
                    this.showDropdown = false;
                    
                    // Set hidden input values
                    const hiddenInput = document.getElementById('produkSelect');
                    hiddenInput.value = product.produk;
                    hiddenInput.dataset.harga = product.harga_jual;
                    hiddenInput.dataset.stok = product.stok;
                    hiddenInput.dataset.hpp = product.hpp;
                }
            }
        }

        const transaksi = [];

        function formatRupiah(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(num || 0);
        }

        function refreshTable() {
            const tbody = document.getElementById('tbody');
            tbody.innerHTML = '';
            let total = 0;
            let labaTotal = 0;
            let itemCount = 0;

            transaksi.forEach((item, index) => {
                const subtotal = item.harga * item.jumlah;
                const laba = (item.harga - item.hpp) * item.jumlah;
                total += subtotal;
                labaTotal += laba;
                itemCount += item.jumlah;

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.produk}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.jumlah}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatRupiah(item.harga)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">${formatRupiah(subtotal)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="hapusItem(${index})" class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('totalRupiah').innerText = formatRupiah(total);
            // FIX: elemen kini tersedia di HTML
            document.getElementById('labaRupiah').innerText = formatRupiah(labaTotal);
            document.getElementById('jumlahItem').innerText = itemCount;

            lucide.createIcons();
        }

        function hapusItem(index) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                transaksi.splice(index, 1);
                refreshTable();
                calculateKembalian();
            }
        }

        function resetTransaksi() {
            if (transaksi.length > 0 && confirm('Apakah Anda yakin ingin mereset semua transaksi?')) {
                transaksi.length = 0;
                refreshTable();
                document.getElementById('pembayaranInput').value = '';
                document.getElementById('kembalianText').innerText = 'Rp 0';
            }
        }

        function calculateKembalian() {
            const total = transaksi.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
            const pembayaran = parseFloat(document.getElementById('pembayaranInput').value) || 0;
            const kembalian = pembayaran - total;
            
            if (pembayaran > 0) {
                document.getElementById('kembalianText').innerText = formatRupiah(Math.max(0, kembalian));
            } else {
                document.getElementById('kembalianText').innerText = 'Rp 0';
            }
        }

        document.getElementById('tambahBtn').addEventListener('click', () => {
            const hiddenInput = document.getElementById('produkSelect');
            const jumlahBaru = parseInt(document.getElementById('jumlahInput').value);
            
            if (!hiddenInput.value || !jumlahBaru || jumlahBaru < 1) {
                alert('Silakan pilih produk dan masukkan jumlah yang valid!');
                return;
            }

            if (jumlahBaru > 999) {
                alert('Jumlah maksimal 999.');
                return;
            }

            const produk = hiddenInput.value;
            const harga = parseFloat(hiddenInput.dataset.harga);
            const hpp = parseFloat(hiddenInput.dataset.hpp);
            const stok = parseInt(hiddenInput.dataset.stok);

            const existing = transaksi.find(item => item.produk === produk);
            if (existing) {
                const totalJumlah = existing.jumlah + jumlahBaru;
                if (totalJumlah > stok) {
                    alert(`Stok tidak cukup! Tersedia: ${stok} ${produk}`);
                    return;
                }
                existing.jumlah = totalJumlah;
            } else {
                if (jumlahBaru > stok) {
                    alert(`Stok tidak cukup! Tersedia: ${stok} ${produk}`);
                    return;
                }
                transaksi.push({ produk, jumlah: jumlahBaru, harga, hpp, stok });
            }

            refreshTable();
            calculateKembalian();
            
            // Reset search input
            const searchComponent = document.querySelector('[x-data="productSearch()"]');
            if (searchComponent && searchComponent.__x) {
                searchComponent.__x.$data.searchTerm = '';
                searchComponent.__x.$data.selectedProduct = '';
            }
            document.getElementById('jumlahInput').value = '';
            
            // Show success feedback
            const btn = document.getElementById('tambahBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i data-lucide="check" class="w-4 h-4 mr-2"></i> Ditambahkan!';
            btn.classList.add('bg-green-600');
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('bg-green-600');
                lucide.createIcons();
            }, 1500);
        });

        document.getElementById('pembayaranInput').addEventListener('input', calculateKembalian);

        // FIX: terima tombol sebagai parameter, jangan andalkan global 'event'
        function submitTransaksi(btn) {
            const total = transaksi.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
            const pembayaran = parseFloat(document.getElementById('pembayaranInput').value);

            if (transaksi.length === 0) {
                alert('Belum ada item dalam transaksi!');
                return;
            }
            if (!pembayaran || pembayaran < total) {
                alert(`Pembayaran tidak cukup! Total: ${formatRupiah(total)}`);
                return;
            }

            const kembalian = pembayaran - total;
            
            // Show loading state
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i> Menyimpan...';
            btn.disabled = true;

            fetch('{{ route("penjualan.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    transaksi,
                    total,
                    pembayaran,
                    kembalian
                })
            })
            .then(res => res.json())
            .then(res => {
                alert('Transaksi berhasil disimpan!');

                printStruk(res.id, transaksi, total, pembayaran, kembalian);
                // Simpan laba ke cash flow
                const labaTotal = transaksi.reduce((sum, item) => sum + ((item.harga - item.hpp) * item.jumlah), 0);

                if (labaTotal > 0) {
                    fetch('{{ route("simpankas.save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            type: 'masuk',
                            category: 'laba_penjualan',
                            amount: labaTotal,
                            description: 'Laba dari penjualan produk',
                            reference: res.id || 'penjualan_' + Date.now()
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Laba disimpan:', data);
                    })
                    .catch(err => {
                        console.error('Gagal simpan laba:', err);
                    });
                }

                transaksi.length = 0;
                refreshTable();
                document.getElementById('pembayaranInput').value = '';
                document.getElementById('kembalianText').innerText = 'Rp 0';
                
                // Reset button
                btn.innerHTML = originalText;
                btn.disabled = false;
                lucide.createIcons();
            })
            .catch(error => {
                console.error(error);
                btn.innerHTML = originalText;
                btn.disabled = false;
                lucide.createIcons();
            });
        }

        function printStruk(noTransaksi, items, total, pembayaran, kembalian) {
            let html = `
                <html>
                <head>
                    <title>Struk Transaksi</title>
                    <style>
                        body { font-family: monospace; font-size: 12px; }
                        .center { text-align: center; }
                        .line { border-top: 1px dashed #000; margin: 4px 0; }
                        table { width: 100%; border-collapse: collapse; }
                        td { padding: 2px 0; }
                    </style>
                </head>
                <body>
                    <div class="center">
                        <h3>KAS PRO</h3>
                        <p>Sistem Kasir Kas</p>
                        <p>No. Transaksi: ${noTransaksi}</p>
                        <div class="line"></div>
                    </div>
                    <table>
                        ${items.map(i => `
                            <tr>
                                <td>${i.produk} (${i.jumlah}x)</td>
                                <td style="text-align:right;">${formatRupiah(i.harga * i.jumlah)}</td>
                            </tr>
                        `).join('')}
                    </table>
                    <div class="line"></div>
                    <table>
                        <tr>
                            <td>Total</td>
                            <td style="text-align:right;">${formatRupiah(total)}</td>
                        </tr>
                        <tr>
                            <td>Tunai</td>
                            <td style="text-align:right;">${formatRupiah(pembayaran)}</td>
                        </tr>
                        <tr>
                            <td>Kembalian</td>
                            <td style="text-align:right;">${formatRupiah(kembalian)}</td>
                        </tr>
                    </table>
                    <div class="line"></div>
                    <p class="center">Terima kasih 🙏<br/>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
                    <script>window.print();<\/script>
                </body>
                </html>
            `;

            let win = window.open('', '', 'width=300,height=600');
            win.document.write(html);
            win.document.close();
        }
    </script>
</body>
</html>
