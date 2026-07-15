<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\SeatType;
use App\Models\Showtime;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->createGenres();
        $this->createMovies();
        $this->createSeatTypes();
        $this->createHalls();
        $this->createShowtimes();
    }

    private function createGenres(): void
    {
        $genres = ['Action', 'Comedy', 'Drama', 'Horror', 'Sci-Fi', 'Romance', 'Thriller', 'Animation', 'Documentary', 'Adventure'];
        foreach ($genres as $name) {
            Genre::create(['name' => $name]);
        }
    }

    private function createMovies(): void
    {
        $genreIds = Genre::pluck('id');

        $movies = [
            ['title' => 'The Quantum Paradox', 'description' => 'A brilliant physicist discovers a way to communicate across parallel universes, but each message threatens to collapse reality itself.', 'duration' => 148, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-06-15', 'rating' => 8.5, 'genres' => ['Sci-Fi', 'Thriller']],
            ['title' => 'Midnight in Seoul', 'description' => 'Two strangers share a chance encounter on the last train in Seoul, leading to an unforgettable night that changes their lives.', 'duration' => 112, 'language' => 'Korean', 'status' => 'now_showing', 'release_date' => '2026-07-01', 'rating' => 7.8, 'genres' => ['Romance', 'Drama']],
            ['title' => 'Shadow Protocol', 'description' => 'A retired spy is pulled back into action when a ghost from her past threatens global security.', 'duration' => 135, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-05-20', 'rating' => 7.2, 'genres' => ['Action', 'Thriller']],
            ['title' => 'The Laughing Clinic', 'description' => 'A down-on-his-luck comedian checks into a wellness clinic where the treatment is laughter, chaos, and unexpected friendships.', 'duration' => 98, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-06-10', 'rating' => 7.5, 'genres' => ['Comedy']],
            ['title' => 'Beneath the Ice', 'description' => 'A research team in Antarctica discovers an ancient organism frozen for millennia that begins to thaw — and evolve.', 'duration' => 142, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-04-05', 'rating' => 8.1, 'genres' => ['Horror', 'Sci-Fi']],
            ['title' => 'The Last Kingdom', 'description' => 'In a war-torn medieval kingdom, a young knight must unite the fractured houses before an invading army destroys everything.', 'duration' => 165, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-03-15', 'rating' => 8.3, 'genres' => ['Action', 'Adventure', 'Drama']],
            ['title' => 'Pixel Perfect', 'description' => 'An animated adventure about a glitch in a video game who discovers she is the key to saving her digital world.', 'duration' => 95, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-07-10', 'rating' => 7.9, 'genres' => ['Animation', 'Adventure', 'Comedy']],
            ['title' => 'Whispers in the Wind', 'description' => 'A deaf woman in rural Japan uncovers a supernatural conspiracy using her unique ability to see sound.', 'duration' => 118, 'language' => 'Japanese', 'status' => 'coming_soon', 'release_date' => '2026-08-20', 'rating' => 0, 'genres' => ['Thriller', 'Drama']],
            ['title' => 'Ocean\'s Ransom', 'description' => 'A crew of misfit pirates plans the heist of the century: stealing a legendary treasure from an underwater fortress.', 'duration' => 128, 'language' => 'English', 'status' => 'coming_soon', 'release_date' => '2026-09-01', 'rating' => 0, 'genres' => ['Adventure', 'Action', 'Comedy']],
            ['title' => 'The Silent Witness', 'description' => 'A courtroom drama where a mute child is the only witness to a high-profile crime, and a determined lawyer fights to give her a voice.', 'duration' => 132, 'language' => 'English', 'status' => 'now_showing', 'release_date' => '2026-05-01', 'rating' => 8.7, 'genres' => ['Drama', 'Thriller']],
        ];

        foreach ($movies as $data) {
            $genres = $data['genres'];
            unset($data['genres']);
            $movie = Movie::create($data);
            $movie->genres()->attach(Genre::whereIn('name', $genres)->pluck('id'));
        }
    }

    private function createSeatTypes(): void
    {
        SeatType::create(['name' => 'Standard', 'color' => '#4A90D9', 'price_multiplier' => 1.0]);
        SeatType::create(['name' => 'Premium', 'color' => '#F5A623', 'price_multiplier' => 1.5]);
        SeatType::create(['name' => 'VIP', 'color' => '#7B68EE', 'price_multiplier' => 2.0]);
        SeatType::create(['name' => 'Couple', 'color' => '#FF6B6B', 'price_multiplier' => 1.8]);
    }

    private function createHalls(): void
    {
        $configs = [
            ['name' => 'Hall 1', 'rows' => 8, 'seatsPerRow' => 12, 'seatTypes' => ['A' => 1, 'B' => 1, 'C' => 1, 'D' => 2, 'E' => 2, 'F' => 2, 'G' => 3, 'H' => 3]],
            ['name' => 'Hall 2 (IMAX)', 'rows' => 10, 'seatsPerRow' => 14, 'seatTypes' => ['A' => 1, 'B' => 1, 'C' => 1, 'D' => 1, 'E' => 2, 'F' => 2, 'G' => 2, 'H' => 2, 'I' => 3, 'J' => 3]],
            ['name' => 'Hall 3', 'rows' => 6, 'seatsPerRow' => 10, 'seatTypes' => ['A' => 1, 'B' => 1, 'C' => 2, 'D' => 2, 'E' => 4, 'F' => 4]],
            ['name' => 'Hall 4 (VIP)', 'rows' => 5, 'seatsPerRow' => 8, 'seatTypes' => ['A' => 3, 'B' => 3, 'C' => 3, 'D' => 3, 'E' => 3]],
        ];

        foreach ($configs as $config) {
            $hall = Hall::create([
                'name' => $config['name'],
                'capacity' => $config['rows'] * $config['seatsPerRow'],
                'is_active' => true,
            ]);

            $rowLetters = array_slice(range('A', 'Z'), 0, $config['rows']);
            foreach ($rowLetters as $rowLetter) {
                $seatTypeId = $config['seatTypes'][$rowLetter] ?? 1;
                for ($num = 1; $num <= $config['seatsPerRow']; $num++) {
                    Seat::create([
                        'hall_id' => $hall->id,
                        'seat_type_id' => $seatTypeId,
                        'row' => $rowLetter,
                        'number' => $num,
                        'label' => $rowLetter . $num,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }

    private function createShowtimes(): void
    {
        $nowShowing = Movie::where('status', 'now_showing')->get();
        $halls = Hall::all();
        $today = now()->startOfDay();

        $schedule = [
            ['day' => 0, 'movie' => 0, 'hall' => 0, 'start' => '10:00', 'end' => '12:28'],
            ['day' => 0, 'movie' => 1, 'hall' => 1, 'start' => '10:00', 'end' => '11:52'],
            ['day' => 0, 'movie' => 2, 'hall' => 2, 'start' => '12:30', 'end' => '14:45'],
            ['day' => 0, 'movie' => 3, 'hall' => 3, 'start' => '12:30', 'end' => '14:08'],
            ['day' => 0, 'movie' => 4, 'hall' => 0, 'start' => '15:00', 'end' => '17:22'],
            ['day' => 0, 'movie' => 5, 'hall' => 1, 'start' => '15:00', 'end' => '17:45'],
            ['day' => 0, 'movie' => 6, 'hall' => 2, 'start' => '17:30', 'end' => '19:05'],
            ['day' => 0, 'movie' => 9, 'hall' => 3, 'start' => '17:30', 'end' => '19:42'],
            ['day' => 0, 'movie' => 0, 'hall' => 2, 'start' => '20:00', 'end' => '22:28'],
            ['day' => 0, 'movie' => 4, 'hall' => 3, 'start' => '20:00', 'end' => '22:22'],
            ['day' => 1, 'movie' => 2, 'hall' => 0, 'start' => '10:00', 'end' => '12:15'],
            ['day' => 1, 'movie' => 5, 'hall' => 1, 'start' => '10:00', 'end' => '12:45'],
            ['day' => 1, 'movie' => 1, 'hall' => 2, 'start' => '12:30', 'end' => '14:22'],
            ['day' => 1, 'movie' => 3, 'hall' => 3, 'start' => '12:30', 'end' => '14:08'],
            ['day' => 1, 'movie' => 0, 'hall' => 0, 'start' => '15:00', 'end' => '17:28'],
            ['day' => 1, 'movie' => 6, 'hall' => 1, 'start' => '15:00', 'end' => '16:35'],
            ['day' => 1, 'movie' => 9, 'hall' => 2, 'start' => '17:30', 'end' => '19:42'],
            ['day' => 1, 'movie' => 4, 'hall' => 3, 'start' => '17:30', 'end' => '19:52'],
            ['day' => 1, 'movie' => 5, 'hall' => 0, 'start' => '20:00', 'end' => '22:45'],
            ['day' => 1, 'movie' => 2, 'hall' => 1, 'start' => '20:00', 'end' => '22:15'],
        ];

        foreach ($schedule as $s) {
            $movie = $nowShowing->get($s['movie']);
            if (!$movie) continue;

            $hall = $halls->get($s['hall']);
            if (!$hall) continue;

            $start = $today->copy()->addDays($s['day'])->setTimeFromTimeString($s['start']);
            $endStr = $s['end'];
            $endHour = (int) substr($endStr, 0, 2);
            $endMinute = (int) substr($endStr, 3, 2);
            $end = $today->copy()->addDays($s['day'])->setTime($endHour, $endMinute);
            if ($endHour < 6) $end->addDay();

            Showtime::create([
                'movie_id' => $movie->id,
                'hall_id' => $hall->id,
                'start_time' => $start,
                'end_time' => $end,
                'base_price' => $s['hall'] === 1 ? 15.00 : ($s['hall'] === 3 ? 20.00 : 10.00),
            ]);
        }
    }
}
