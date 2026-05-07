<template>
<div class="min-h-screen bg-slate-950 text-white font-sans">
<nav class="bg-slate-900 border-b-2 border-purple-600 p-4 flex justify-between items-center shadow-lg shadow-purple-500/20">
<span class="text-2xl font-black text-purple-400 tracking-tighter uppercase italic">FrikiFest VUE</span>
<div class="space-x-4">
<button class="hover:text-purple-400 transition">Inicio</button>
<button class="bg-purple-600 px-4 py-2 rounded-full font-bold hover:bg-purple-700 transition">Eventos</button>
</div>
</nav>
<main class="max-w-5xl mx-auto p-6">
<slot /> <!-- Aquí se cargará el contenido de cada página -->
</main>
<footer class="text-center p-8 border-t border-slate-800 text-slate-500">
FrikiFest &copy; 2026 - SPA Power con Laravel & Vue en Universidad La Salle Chihuahua
</footer>
</div>
</template>
