<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Deze seeder maakt testposts aan en koppelt ze
     * meteen aan users en categories.
     */
    public function run(): void
    {
        $users = User::query()->pluck('id')->all();
        $categories = Category::query()->pluck('id')->all();

        /**
         * Zonder users of categories heeft het weinig zin
         * om posts aan te maken.
         */
        if (empty($users) || empty($categories)) {
            return;
        }

        $posts = [
            [
                'title' => 'Laravel routing uitgelegd voor beginners',
                'excerpt' => 'Een praktische introductie tot routes, resource controllers en route model binding in Laravel.',
                'body' => 'In dit artikel bekijken we stap voor stap hoe routing werkt in Laravel. We bespreken eenvoudige routes, routegroepen, resource routes en route model binding. Daarna tonen we hoe je een nette backendstructuur opbouwt voor een professioneel project.',
                'is_published' => true,
                'category_names' => ['Laravel', 'PHP'],
            ],
            [
                'title' => 'Wat is het verschil tussen Eloquent en Query Builder',
                'excerpt' => 'We vergelijken twee belangrijke manieren om data op te halen in Laravel.',
                'body' => 'Laravel biedt meerdere manieren om met data te werken. In dit artikel vergelijken we Eloquent en de Query Builder. We bekijken leesbaarheid, flexibiliteit, performance en typische use cases zodat je beter weet wanneer je welke aanpak gebruikt.',
                'is_published' => true,
                'category_names' => ['Laravel', 'PHP', 'MySQL'],
            ],
            [
                'title' => 'Een blogmodule bouwen met categories en posts',
                'excerpt' => 'We bouwen een complete backendmodule met CRUD, filters en relaties.',
                'body' => 'Een goede blogmodule bestaat niet alleen uit een posts-tabel. Je hebt ook categories, many-to-many relaties, valide routes, FormRequests en een nette admininterface nodig. In dit artikel bouwen we zo een module stap voor stap op.',
                'is_published' => true,
                'category_names' => ['Laravel', 'MySQL'],
            ],
            [
                'title' => 'Waarom slugs belangrijk zijn in een CMS',
                'excerpt' => 'Mensvriendelijke en SEO-vriendelijke URLs zijn een basisprincipe in contentbeheer.',
                'body' => 'Een slug zorgt voor duidelijke en stabiele URLs. In een CMS gebruik je slugs meestal voor categories en posts. We tonen hoe je ze automatisch genereert, hoe je uniciteit bewaakt en hoe je ze inzet voor route model binding.',
                'is_published' => false,
                'category_names' => ['SEO', 'Laravel'],
            ],
            [
                'title' => 'Livewire of Vue in een modern Laravel project',
                'excerpt' => 'Een eerlijke vergelijking tussen twee populaire manieren om interactieve interfaces te bouwen.',
                'body' => 'Sommige projecten hebben baat bij Livewire omdat het dicht bij Laravel blijft. Andere projecten vragen een meer uitgesproken frontendlaag, waar Vue.js beter past. We bespreken de sterktes, zwaktes en typische scenario’s.',
                'is_published' => true,
                'category_names' => ['Laravel', 'Livewire', 'Vue.js', 'JavaScript'],
            ],
            [
                'title' => 'AI workflows voor kleine ondernemingen',
                'excerpt' => 'Hoe AI-agents en automatisaties nuttig kunnen zijn voor KMO’s en zelfstandigen.',
                'body' => 'AI is niet alleen iets voor grote bedrijven. Ook kleine ondernemingen kunnen vandaag processen automatiseren met slimme workflows. Denk aan leadopvolging, contentvoorstellen, FAQ-behandeling en interne assistenten.',
                'is_published' => true,
                'category_names' => ['AI', 'SEO'],
            ],
        ];

        foreach ($posts as $item) {
            /**
             * We kiezen willekeurig een bestaande user als auteur.
             * In een cursuscontext is dat voldoende voor testdata.
             */
            $userId = fake()->randomElement($users);

            $post = Post::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'user_id' => $userId,
                    'title' => $item['title'],
                    'slug' => Str::slug($item['title']),
                    'excerpt' => $item['excerpt'],
                    'body' => $item['body'],
                    'is_published' => $item['is_published'],
                    'published_at' => $item['is_published'] ? now()->subDays(rand(1, 30)) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            /**
             * We zoeken de category IDs op basis van hun naam
             * en koppelen die via de many-to-many pivot.
             */
            $categoryIds = Category::query()
                ->whereIn('name', $item['category_names'])
                ->pluck('id')
                ->all();

            $post->categories()->sync($categoryIds);
        }
    }
}
