@extends('layouts.main')

@section('content')
    <h1>{{ $lesson->title }}</h1>
    <p>{{ $lesson->content }}</p>
    <a href="{{ route('lessons.showTest', ['lesson' => $lesson]) }}">Inizia il Test</a>

    <h2>Domande Interattive</h2>

    {{-- Ciclo per mostrare le domande --}}
    @foreach ($lesson->questions as $question)
        <div class="question">
            <p>{{ $question->question_text }}
                <button onclick="speakText('{{ $question->question_text }}')">🔊</button>
            </p>
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <p>Punteggio:
                {{ $citizen->citizenAnswers()->where('is_correct', true)->count() }}/{{ $lesson->questions->count() }}</p>

            <form method="POST" action="{{ route('lessons.check_answer', $question->id) }}">
                @csrf
                @foreach ($question->options as $option)
                    <div>
                        <input type="radio" name="answer" value="{{ $option }}" required>
                        <label>{{ $option }}</label>
                        <button type="button" onclick="speakText('{{ $option }}')">🔊</button>
                    </div>
                @endforeach
                <button type="submit">Verifica</button>
            </form>
            {{-- Feedback visivo con animazione --}}
            @if (session('is_correct') !== null)
                <div
                    class="feedback animate__animated {{ session('is_correct') ? 'animate__bounceIn' : 'animate__shakeX' }}">
                    <p style="color: {{ session('is_correct') ? 'green' : 'red' }}">
                        {{ session('status') }}
                    </p>
                </div>
            @endif
        </div>
    @endforeach

    <script>
        function speakText(text) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'it-IT'; // Lingua per l’italiano, adattabile per altri accenti
            speechSynthesis.speak(utterance);
        }

        function playSound(isCorrect) {
            const audio = new Audio(isCorrect ? '/sounds/correct.mp3' : '/sounds/incorrect.mp3');
            audio.play();
        }

        // Esegui il suono in base alla risposta
        document.addEventListener('DOMContentLoaded', function() {
            const totalQuestions = {{ $lesson->questions->count() }};
            const correctAnswers = {{ $citizen->citizenAnswers()->where('is_correct', true)->count() }};
            const progressPercentage = (correctAnswers / totalQuestions) * 100;
            @if (session('is_correct') !== null)
                playSound({{ session('is_correct') ? 'true' : 'false' }});
            @endif

            document.getElementById('progress-bar').style.width = progressPercentage + "%";

        });
    </script>
@endsection
