<script setup>
import FrikiLayout from '@/Layouts/FrikiLayout.vue';
import { useForm, Head, usePage } from '@inertiajs/vue3';

defineProps({ eventos: Array });

const page = usePage();
const form = useForm({
nombre: '',
categoria: 'Anime',
fecha: '',
descripcion: ''
});
const submit = () => {
form.post(route('eventos.store'), {
onSuccess: () => form.reset(),
});
};
</script>
<template>
<FrikiLayout>
<Head title="Portal Friki"/>
<p
  v-if="page.props.flash?.success"
  class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-center text-sm text-emerald-300"
  role="status"
>
  {{ page.props.flash.success }}
</p>
<div class="grid md:grid-cols-2 gap-10">
<!-- SECCIÓN: REGISTRO -->
<section>
<h1 class="text-3xl font-bold mb-6 text-purple-400 italic">¡Da de alta tu evento!</h1>
<form @submit.prevent="submit" class="bg-slate-900 p-6 rounded-2xl border border-slate-800 space-y-4">
<div>
<label class="block text-sm mb-1">Nombre del Evento</label>
<input v-model="form.nombre" type="text" class="w-full bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-purple-500">
<p v-if="form.errors.nombre" class="text-red-400 text-xs mt-1">{{ form.errors.nombre }}</p>
</div>
<div>
<label class="block text-sm mb-1">Categoría</label>
<select v-model="form.categoria" class="w-full bg-slate-800 border-none rounded-lg">
<option>Anime</option>
<option>Cómics</option>
<option>Videojuegos</option>
</select>
<p v-if="form.errors.categoria" class="text-red-400 text-xs mt-1">{{ form.errors.categoria }}</p>
</div>
<div>
<label class="block text-sm mb-1">Fecha Estelar</label>
<input v-model="form.fecha" type="date" class="w-full bg-slate-800 border-none rounded-lg">
<p v-if="form.errors.fecha" class="text-red-400 text-xs mt-1">{{ form.errors.fecha }}</p>
</div>
<div>
<label class="block text-sm mb-1">Descripción</label>
<textarea v-model="form.descripcion" class="w-full bg-slate-800 border-none rounded-lg" rows="3"></textarea>
<p v-if="form.errors.descripcion" class="text-red-400 text-xs mt-1">{{ form.errors.descripcion }}</p>
</div>
<button :disabled="form.processing" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 py-3 rounded-xl font-black uppercase tracking-widest hover:scale-105 transition-transform disabled:opacity-50">
{{ form.processing ? 'Cargando...' : 'Registrar Evento ' }}
</button>
</form>
</section>
<!-- SECCIÓN: LISTADO -->
<section>
<h2 class="text-3xl font-bold mb-6 text-pink-400 italic">Próximos Eventos</h2>
<div class="space-y-4">
<div v-for="evento in eventos" :key="evento.id" class="bg-slate-900 p-4 rounded-xl border-l-4 border-pink-500 shadow-md">
<span class="text-xs font-bold uppercase text-pink-500">{{ evento.categoria }}</span>
<h3 class="text-xl font-bold">{{ evento.nombre }}</h3>
<p class="text-slate-400 text-sm italic">{{ evento.fecha }}</p>
<p class="mt-2 text-slate-300">{{ evento.descripcion }}</p>
</div>
<div v-if="eventos.length === 0" class="text-slate-500 italic">No hay eventos... aún.</div>
</div>
</section>
</div>
</FrikiLayout>
</template>
