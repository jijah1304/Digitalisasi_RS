<nav class="glass sticky top-0 z-50 px-6 py-4 mb-8 transition-all duration-300">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        
        <!-- BAGIAN KIRI: Logo & Menu Utama -->
        <div class="flex items-center gap-10">
            <!-- Logo Brand -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-rs-green rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                    K
                </div>
                <span class="text-xl font-bold text-rs-navy hidden sm:block tracking-tight group-hover:text-rs-green transition-colors">
                    KitaBisaSehat
                </span>
            </a>

            <!-- MENU LINKS (Muncul Sesuai Role) -->
            <div class="hidden md:flex items-center gap-6">
                <!-- Link Dashboard (Semua Role) -->
                <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                    Dashboard
                </a>

                <!-- MENU KHUSUS ADMIN -->
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                        Pengguna
                    </a>
                    <a href="{{ route('polis.index') }}" class="text-sm font-medium {{ request()->routeIs('polis.*') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                        Poli
                    </a>
                    <a href="{{ route('medicines.index') }}" class="text-sm font-medium {{ request()->routeIs('medicines.*') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                        Obat
                    </a>
                
                <!-- MENU KHUSUS DOKTER -->
                @elseif(Auth::user()->role === 'dokter')
                    <a href="{{ route('doctor.patients.index') }}" class="text-sm font-medium {{ request()->routeIs('doctor.patients.*') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                        Riwayat Pasien
                    </a>
                    <a href="{{ route('schedules.index') }}" class="text-sm font-medium {{ request()->routeIs('schedules.*') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                        Jadwal Praktik
                    </a>

                <!-- MENU KHUSUS PASIEN -->
                @elseif(Auth::user()->role === 'pasien')
                    <a href="{{ route('appointments.create') }}" class="text-sm font-medium {{ request()->routeIs('appointments.create') ? 'text-rs-green font-bold' : 'text-rs-navy/70 hover:text-rs-teal' }} transition-colors">
                        Buat Janji
                    </a>
                @endif
            </div>
        </div>

        <!-- BAGIAN KANAN: User Profile & Logout -->
        <div class="flex items-center gap-3 sm:gap-4">
            
            <!-- Info User (Nama & Role) -->
            <div class="text-right hidden sm:block leading-tight">
                <div class="text-sm font-bold text-rs-navy">{{ Auth::user()->name }}</div>
                <!-- Link Edit Profil -->
                <a href="{{ route('profile.edit') }}" class="text-xs text-rs-teal hover:underline font-medium uppercase tracking-wider block" title="Edit Profil">
                    Edit Profil
                </a>
            </div>
            
            <!-- Avatar (Klik untuk ke Profil) -->
            <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-full bg-rs-pale border-2 border-white shadow-sm overflow-hidden shrink-0 block hover:ring-2 hover:ring-rs-green transition-all" title="Edit Profil">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=366b2b&color=fff&size=128" 
                     alt="Avatar {{ Auth::user()->name }}" 
                     class="w-full h-full object-cover">
            </a>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}" class="ml-1">
                @csrf
                <button type="submit" 
                        class="p-2 rounded-xl text-rs-navy hover:bg-red-50 hover:text-red-600 transition-all duration-300 border border-transparent hover:border-red-100 group" 
                        title="Keluar / Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>