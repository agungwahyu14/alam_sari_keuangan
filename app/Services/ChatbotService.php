<?php

namespace App\Services;

use App\Models\ChatbotFaq;
use Illuminate\Support\Str;

class ChatbotService
{
    /**
     * Default response ketika tidak ada jawaban yang cocok
     */
    const DEFAULT_RESPONSE = "Maaf, saya tidak mengerti pertanyaan Anda. Silakan hubungi admin untuk bantuan lebih lanjut atau coba tanyakan dengan cara lain.";

    /**
     * Mencari jawaban berdasarkan input user
     *
     * @param string $userInput
     * @return array
     */
    public function findAnswer(string $userInput): array
    {
        // Normalisasi input
        $normalizedInput = $this->normalizeText($userInput);
        
        // Cari jawaban dengan scoring
        $bestMatch = $this->searchWithScoring($normalizedInput);

        if ($bestMatch) {
            return [
                'success' => true,
                'answer' => $bestMatch->answer,
                'question' => $bestMatch->question,
                'confidence' => 'high',
            ];
        }

        // Jika tidak ada yang cocok, return default response
        return [
            'success' => false,
            'answer' => self::DEFAULT_RESPONSE,
            'question' => null,
            'confidence' => 'none',
        ];
    }

    /**
     * Normalisasi teks untuk pencarian yang lebih baik
     *
     * @param string $text
     * @return string
     */
    private function normalizeText(string $text): string
    {
        // Convert ke lowercase
        $text = Str::lower($text);
        
        // Hapus karakter special, hanya simpan alphanumeric dan spasi
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        
        // Hapus multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    /**
     * Cari FAQ dengan sistem scoring
     *
     * @param string $normalizedInput
     * @return ChatbotFaq|null
     */
    private function searchWithScoring(string $normalizedInput): ?ChatbotFaq
    {
        $faqs = ChatbotFaq::active()->get();
        $bestMatch = null;
        $highestScore = 0;
        $threshold = 0.3; // Minimum score 30% untuk dianggap match

        foreach ($faqs as $faq) {
            $score = 0;
            
            // Score 1: Cek kecocokan dengan pertanyaan (bobot tinggi)
            $normalizedQuestion = $this->normalizeText($faq->question);
            $questionScore = $this->calculateSimilarity($normalizedInput, $normalizedQuestion);
            $score += $questionScore * 3; // Bobot 3x

            // Score 2: Cek kecocokan dengan keywords (bobot medium)
            if ($faq->keywords && is_array($faq->keywords)) {
                $keywordScore = 0;
                foreach ($faq->keywords as $keyword) {
                    $normalizedKeyword = $this->normalizeText($keyword);
                    // Cek apakah keyword ada dalam input
                    if (Str::contains($normalizedInput, $normalizedKeyword)) {
                        $keywordScore += 0.5;
                    }
                }
                $score += min($keywordScore, 2); // Max score dari keywords = 2
            }

            // Score 3: Cek word overlap (bobot rendah)
            $wordsInput = explode(' ', $normalizedInput);
            $wordsQuestion = explode(' ', $normalizedQuestion);
            $overlap = count(array_intersect($wordsInput, $wordsQuestion));
            $overlapScore = $overlap / max(count($wordsInput), count($wordsQuestion));
            $score += $overlapScore;

            // Normalisasi total score ke range 0-1
            $normalizedScore = $score / 6; // Total bobot maksimal = 6

            // Update best match jika score lebih tinggi
            if ($normalizedScore > $highestScore && $normalizedScore >= $threshold) {
                $highestScore = $normalizedScore;
                $bestMatch = $faq;
            }
        }

        return $bestMatch;
    }

    /**
     * Hitung similarity antara dua string menggunakan similar_text
     *
     * @param string $str1
     * @param string $str2
     * @return float (0-1)
     */
    private function calculateSimilarity(string $str1, string $str2): float
    {
        similar_text($str1, $str2, $percent);
        return $percent / 100;
    }

    /**
     * Dapatkan suggested questions untuk user
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getSuggestedQuestions(int $limit = 5)
    {
        return ChatbotFaq::active()
            ->inRandomOrder()
            ->limit($limit)
            ->get(['id', 'question']);
    }
}
