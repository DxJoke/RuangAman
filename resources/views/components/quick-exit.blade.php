<!-- Komponen Tombol Keluar Cepat (Quick Exit) -->
<div x-data="{
    quickExit() {
        // Hapus token session sensitif lokal jika ada
        sessionStorage.clear();
        // Redirect seketika ke halaman umum netral
        window.location.replace('https://www.google.com');
    }
}" 
@keydown.escape.window="quickExit()"
class="fixed bottom-4 right-4 z-50">
    <button 
        @click="quickExit()" 
        type="button" 
        class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-full shadow-lg transition duration-200 focus:outline-none focus:ring-4 focus:ring-red-300"
        title="Pintasan cepat: Tekan tombol Esc untuk langsung keluar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
        </svg>
        <span>Keluar Cepat (ESC)</span>
    </button>
</div>
