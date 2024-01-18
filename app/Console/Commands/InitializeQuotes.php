<?php

namespace App\Console\Commands;

use App\Actions\AI\CreateEmbedding;
use App\Models\Quote;
use Illuminate\Console\Command;

class InitializeQuotes extends Command
{
    protected $signature = 'app:init-quotes';
    protected $description = 'Bootstrap the quotes table with quotes';

    private array $quotes = [
        [
            'quote' => 'You started this wrong by saying English Muffin',
            'author' => 'Max Landes',
        ],
        [
            'quote' => 'I am darkness.',
            'author' => 'Joel Haker',
        ],
        [
            'quote' => 'I\'ll put fried egg on just about anything',
            'author' => 'Brad Bautista',
        ],
        [
            'quote' => 'No, don\'t touch me. And that\'s correct.',
            'author' => 'Justin Davis',
        ],
        [
            'quote' => 'Well, the map is not mapping..',
            'author' => 'Mo Munene',
        ],
        [
            'quote' => 'Don\'t get toothpaste in your eye. It\'ll be one of the most painful bathroom experiences you\'ll ever have',
            'author' => 'Charles Reffett',
        ],
        [
            'quote' => 'What I’m wearing to the holiday party, is illegal in three countries',
            'author' => 'Connor Tumbleson',
        ],
        [
            'quote' => 'I don\'t remember much of the 90\'s... That\'s how you know it was a good decade',
            'author' => 'Steph Hiday',
        ],
        [
            'quote' => 'I’m going to eat the dirtiest churro',
            'author' => 'Joe Sullivan',
        ],
        [
            'quote' => 'I want to meet the person who wrote this code and unshake their hand',
            'author' => 'Nils De La Guardia',
        ],
        [
            'quote' => 'I hate eating mashed potatoes in the heat',
            'author' => 'Brittany Diehm',
        ],
        [
            'quote' => 'Back in my tequila Crystal days.',
            'author' => 'Crystal McCusker',
        ],
        [
            'quote' => 'if you hunt somebody people will look at you weird.',
        ],
        [
            'quote' => 'I don\'t need skin',
            'author' => 'Ambyr Lix',
        ],
        [
            'quote' => 'We don\'t just sell the magic beans!',
            'author' => 'Bobby Dickinson',
        ],
        [
            'quote' => 'Every time I think even 20 years into the future, I think of a hellscape',
            'author' => 'Jerred Wernke',
        ],
        [
            'quote' => 'I like rocks.',
            'author' => 'Emily Eaton',
        ],
        [
            'quote' => 'If I went looking for alcohol and I found that, I’d be very disappointed',
            'author' => 'Jeremie Roberts',
        ],
        [
            'quote' => 'No licking your co-workers!',
            'author' => 'Eve Mitts',
        ],
        [
            'quote' => 'Don\'t give me a reason to take a picture of your ankles!',
            'author' => 'Deb Neff',
        ],
    ];

    public function handle(): int
    {
        $this->info('Populating quotes table...');

        foreach ($this->quotes as $quote) {
            $this->info('Adding quote: '.$quote['quote']);

            $quote = Quote::updateOrCreate($quote);
            if (!$quote->getEmbedding('quote')) {
                $quote->embeddings()->create([
                    'column_name' => 'quote',
                    'embedding' => CreateEmbedding::resolve()->execute($quote->quote)->toArray(),
                ]);
            }
            if ($quote->author && !$quote->getEmbedding('author')) {
                $quote->embeddings()->create([
                    'column_name' => 'author',
                    'embedding' => CreateEmbedding::resolve()->execute($quote->author)->toArray(),
                ]);
            }
        }

        $this->info('Done populating quotes table.');

        return Command::SUCCESS;
    }
}
