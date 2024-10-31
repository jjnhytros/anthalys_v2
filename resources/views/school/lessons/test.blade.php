@extends('layouts.main')

@section('content')
    <h1>Test per {{ $lesson->title }}</h1>

    <form method="POST" action="{{ route('lessons.submitTest', $lesson->id) }}">
        @csrf
        @foreach ($questions as $index => $question)
            <div class="question">
                <p>{{ $index + 1 }}. {{ $question['question_text'] }}</p>
                @foreach ($question['options'] as $option)
                    <div>
                        <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" required>
                        <label>{{ $option }}</label>
                    </div>
                @endforeach
                <input type="hidden" name="correct_answers[{{ $index }}]" value="{{ $question['correct_answer'] }}">
            </div>
        @endforeach

        <button type="submit">Invia Risposte</button>
    </form>
@endsection
