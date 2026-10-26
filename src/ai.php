<?php
declare(strict_types=1);

function ai_enabled(): bool
{
    return (string)config('ai.api_key', '') !== '';
}

/**
 * Build an insight from grades only. Privacy by design: no name, no health
 * data, no contact info is sent to the AI provider — just anonymous scores.
 * Returns the text, or throws RuntimeException.
 */
function generate_insight(array $student, string $lang): string
{
    $grades = q_all('SELECT sub.name AS subject, g.term, g.assessment, g.score, g.max_score, g.created_at
                     FROM grades g JOIN subjects sub ON sub.id = g.subject_id
                     WHERE g.student_id = ? ORDER BY g.created_at', [$student['id']]);
    if (!$grades) {
        throw new RuntimeException(t('ai.no_grades'));
    }

    $lines = array_map(fn($g) => sprintf('%s | %s | %s | %s/%s',
        $g['subject'], $g['term'], $g['assessment'], rtrim(rtrim($g['score'], '0'), '.'), rtrim(rtrim($g['max_score'], '0'), '.')), $grades);
    $age = age_from_dob($student['date_of_birth']);
    $language = $lang === 'ar' ? 'Arabic' : 'English';

    $system = 'You help school teachers understand a student\'s academic progress. '
        . 'Write a short, balanced insight for the teacher: strengths, areas needing support, trends over time, '
        . 'and two or three concrete classroom suggestions. Base every statement only on the scores given. '
        . 'Do not diagnose, label, or speculate about the student\'s health, family, or personality. '
        . 'Use cautious language ("may", "suggests"). Under 200 words. Plain text, short paragraphs or simple dashes. '
        . "Write in {$language}.";

    $user = "Student age: " . ($age ?? 'unknown') . "\nScores (subject | term | assessment | score):\n" . implode("\n", $lines);

    $payload = json_encode([
        'model'      => config('ai.model', 'claude-sonnet-5-5'),
        'max_tokens' => (int)config('ai.max_tokens', 800),
        'system'     => $system,
        'messages'   => [['role' => 'user', 'content' => $user]],
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_HTTPHEADER     => [
            'content-type: application/json',
            'x-api-key: ' . config('ai.api_key'),
            'anthropic-version: 2023-06-01',
        ],
        CURLOPT_POSTFIELDS     => $payload,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        throw new RuntimeException(t('ai.failed') . ' (' . $err . ')');
    }
    $data = json_decode($body, true);
    if ($status !== 200 || !isset($data['content'])) {
        $msg = $data['error']['message'] ?? ('HTTP ' . $status);
        throw new RuntimeException(t('ai.failed') . ' (' . $msg . ')');
    }
    $text = '';
    foreach ($data['content'] as $block) {
        if (($block['type'] ?? '') === 'text') $text .= $block['text'];
    }
    return trim($text);
}
