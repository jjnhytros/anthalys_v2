<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Models\School\Lesson;
use App\Models\School\Question;
use App\Http\Controllers\Controller;
use App\Models\School\CitizenAnswer;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::all();
        return view('school.lessons.index', compact('lessons'));
    }

    public function create()
    {
        return view('school.lessons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        Lesson::create($request->all());

        return redirect()->route('school.lessons.index')->with('success', 'Lezione creata con successo.');
    }

    public function show($id)
    {
        $lesson = Lesson::with('questions')->findOrFail($id);
        $citizen = Auth::user()->citizen; // Recupera il cittadino associato all'utente autenticato
        return view('school.lessons.show', compact('lesson', 'citizen'));
    }

    public function edit(Lesson $lesson)
    {
        return view('school.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $lesson->update($request->all());

        return redirect()->route('school.lessons.index')->with('success', 'Lezione aggiornata con successo.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('school.lessons.index')->with('success', 'Lezione eliminata con successo.');
    }

    public function checkAnswer(Request $request, $questionId)
    {
        $citizen = Auth::user()->citizen; // Assumendo che l'utente autenticato abbia una relazione con `Citizen`

        $question = Question::findOrFail($questionId);
        $isCorrect = $request->input('answer') === $question->correct_answer;

        // Salva la risposta del cittadino
        CitizenAnswer::create([
            'citizen_id' => $citizen->id,
            'lesson_question_id' => $question->id,
            'selected_answer' => $request->input('answer'),
            'is_correct' => $isCorrect,
        ]);

        return back()->with([
            'status' => $isCorrect ? 'Risposta corretta!' : 'Risposta sbagliata, riprova.',
            'is_correct' => $isCorrect,
        ]);
    }
    public function showTest(Lesson $lesson)
    {
        // $lesson è ora un'istanza del modello Lesson
        $questions = $this->generateQuestionsFromContent($lesson);

        return view('school.lessons.test', compact('lesson', 'questions'));
    }

    private function generateQuestionsFromContent($lesson)
    {
        $content = $lesson->content;
        $keywords = $this->extractKeywords($content);
        $questions = [];
        $faker = \Faker\Factory::create();

        // Configurazione dell'alfabeto Anthaliano
        $alphabetConfig = config('ath.alphabet');
        $vowels = array_map('strtoupper', $alphabetConfig['vowels']);
        $consonants = array_map('strtoupper', $alphabetConfig['consonants']);

        // Estrai le pronunce dal contenuto della lezione
        $pronunciations = $this->extractPronunciationsFromContent($content);

        foreach ($keywords as $keyword => $meaning) {
            if (strtolower($keyword) === 'vocali') {
                $questionText = "Quali sono le \"$keyword\"?";
                $correctAnswer = implode(', ', $vowels); // Vocali in maiuscolo

                // Distrattori: gruppi casuali di vocali
                $distractors = [];
                for ($i = 0; $i < 2; $i++) {
                    $distractorLetters = $faker->randomElements($vowels, rand(3, 5));
                    $distractors[] = implode(', ', $distractorLetters);
                }

                $options = array_merge($distractors, [$correctAnswer]);
                shuffle($options);
            } elseif (strtolower($keyword) === 'consonanti') {
                $questionText = "Quali sono le \"$keyword\"?";
                $correctAnswer = implode(', ', $consonants); // Consonanti corrette in ordine

                // Distrattori: tutte le consonanti, ma in ordine diverso
                $distractors = [];
                for ($i = 0; $i < 2; $i++) {
                    $shuffledConsonants = $consonants;
                    shuffle($shuffledConsonants); // Disordina le consonanti
                    $distractors[] = implode(', ', $shuffledConsonants);
                }

                $options = array_merge($distractors, [$correctAnswer]);
                shuffle($options);
            } elseif (isset($pronunciations[$keyword])) {
                // Domanda sulla pronuncia della lettera
                $questionText = "Qual è la pronuncia di \"$keyword\"?";
                $correctAnswer = $pronunciations[$keyword];

                // Distrattori variati per la pronuncia
                $distractors = $this->generatePronunciationDistractors($correctAnswer, $pronunciations);
                $options = array_merge($distractors, [$correctAnswer]);
                shuffle($options);
            } else {
                // Domanda generica sul significato
                $questionText = "Qual è il significato di \"$keyword\"?";
                $correctAnswer = $meaning;

                // Distrattori basati su altri significati
                $distractors = array_filter(array_values($keywords), fn($value) => $value !== $correctAnswer);
                $options = array_merge(array_slice($distractors, 0, 2), [$correctAnswer]);
                shuffle($options);
            }

            $questions[] = [
                'question_text' => $questionText,
                'options' => $options,
                'correct_answer' => $correctAnswer,
            ];
        }

        return $questions;
    }
    public function submitTest(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $correctCount = 0;
        $totalQuestions = count($request->input('answers'));

        foreach ($request->input('answers') as $index => $answer) {
            if ($answer === $request->input('correct_answers')[$index]) {
                $correctCount++;
            }
        }

        return redirect()->route('lessons.showTest', $id)->with([
            'status' => "Hai risposto correttamente a $correctCount su $totalQuestions domande.",
        ]);
    }

    private function extractKeywords($content)
    {
        $keywords = [];

        // Divide il contenuto in righe
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            // Rimuovi spazi bianchi iniziali e finali
            $line = trim($line);

            // Cerca linee che iniziano con "- " e sono seguite da una parola chiave e dal suo significato
            if (preg_match('/^- ([^:]+):\s*(.+)/', $line, $matches)) {
                $keyword = trim($matches[1]);  // Rimuove eventuali spazi dalla parola chiave
                $meaning = trim($matches[2]);  // Rimuove eventuali spazi dal significato
                $keywords[$keyword] = $meaning;
            }
        }

        return $keywords;
    }

    // Funzione per estrarre le pronunce dal contenuto della lezione
    private function extractPronunciationsFromContent($content)
    {
        $pronunciations = [];

        // Divide il contenuto in righe e cerca pattern come "- A: suono come..."
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (preg_match('/^- ([^:]+):\s*(.+)/', trim($line), $matches)) {
                $letter = $matches[1];
                $pronunciation = $matches[2];
                $pronunciations[$letter] = $pronunciation;
            }
        }

        return $pronunciations;
    }


    // Funzione per generare distrattori variati per la pronuncia con parole di esempio diverse
    private function generatePronunciationDistractors($correctAnswer, $pronunciations)
    {
        $basePhrases = [
            "Suono simile a",
            "Simile alla pronuncia di",
            "Richiama il suono della",
            "Ricorda il suono della",
        ];

        // Parole di esempio alternative
        $exampleWordMap = [
            'amare' => ['andare', 'cantare', 'sognare'],
            'per' => ['pieno', 'vero', 'certo'],
            'fino' => ['piano', 'vino', 'cino'],
            'sogno' => ['sole', 'fuoco', 'ponte'],
            'luna' => ['ruota', 'suono', 'nave'],
            'cielo' => ['casa', 'cima', 'sala'],
            'gelo' => ['gioco', 'giostra', 'gomma'],
            'cera' => ['cena', 'centro', 'cima'],
            'giorno' => ['giugno', 'gioco', 'gennaio'],
            'sciarpa' => ['scena', 'scienza', 'scala'],
        ];

        $distractors = [];
        $usedWords = []; // Per evitare ripetizioni di parole di esempio

        foreach (array_rand($basePhrases, 2) as $index) {
            $basePhrase = $basePhrases[$index];

            // Genera distrattori con parole di esempio variate
            $distractor = preg_replace_callback('/\b(\w+)\b/', function ($matches) use ($exampleWordMap, &$usedWords) {
                $word = $matches[1];
                // Sostituisci solo se ci sono alternative non usate
                if (isset($exampleWordMap[$word])) {
                    $unusedAlternatives = array_diff($exampleWordMap[$word], $usedWords);
                    if ($unusedAlternatives) {
                        $newWord = $unusedAlternatives[array_rand($unusedAlternatives)];
                        $usedWords[] = $newWord;
                        return $newWord;
                    }
                }
                return $word;
            }, $correctAnswer);

            $distractors[] = ucfirst($basePhrase . ' ' . $distractor);
        }

        // Seleziona una pronuncia diversa dal corretto per ulteriore variabilità
        $otherPronunciations = array_filter($pronunciations, fn($p) => $p !== $correctAnswer);
        $extraDistractor = array_values($otherPronunciations)[rand(0, count($otherPronunciations) - 1)];

        // Aggiungi i distrattori generati e l'ulteriore distrattore casuale
        $distractors[] = $extraDistractor;

        return $distractors;
    }
}
