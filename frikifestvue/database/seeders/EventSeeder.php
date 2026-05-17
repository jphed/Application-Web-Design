<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'nombre' => 'Friki Fest Anime 2026',
            'categoria' => 'Anime',
            'fecha' => '2026-07-15',
            'descripcion' => 'El festival de anime más grande del año con invitados especiales, concursos de cosplay y venta de merchandising exclusivo. No te pierdas las presentaciones de los mejores estudios de animación japonesa.',
        ]);

        Event::create([
            'nombre' => 'Comic Con Latinoamérica',
            'categoria' => 'Comics',
            'fecha' => '2026-09-20',
            'descripcion' => 'Encuentro de artistas de cómics, firmas de autógrafos y lanzamientos exclusivos de tus personajes favoritos. Contará con la presencia de reconocidos guionistas y dibujantes de Marvel y DC.',
        ]);

        Event::create([
            'nombre' => 'Gaming Championship',
            'categoria' => 'Videojuegos',
            'fecha' => '2026-08-10',
            'descripcion' => 'Torneo de videojuegos con premios en efectivo. Participa en Fortnite, League of Legends, Valorant y más. La competencia más intensa del año con jugadores profesionales.',
        ]);

        Event::create([
            'nombre' => 'Cosplay Master Class',
            'categoria' => 'Cosplay',
            'fecha' => '2026-06-25',
            'descripcion' => 'Talleres de cosplay con maestros reconocidos internacionalmente. Aprende técnicas avanzadas de fabricación de disfraces, maquillaje especial y efectos especiales.',
        ]);
    }
}
