<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body_html',
        'body_text',
        'type',
        'variables',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * Get the user who created this template.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this template.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Render the email template with given variables.
     */
    public function render(array $data = []): array
    {
        $subject = $this->replacePlaceholders($this->subject, $data);
        $bodyHtml = $this->replacePlaceholders($this->body_html, $data);
        $bodyText = $this->body_text ? $this->replacePlaceholders($this->body_text, $data) : strip_tags($bodyHtml);

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }

    /**
     * Replace placeholders in template content.
     */
    protected function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}
