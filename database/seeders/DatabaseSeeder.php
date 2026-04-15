<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\{User, Category, Project, Bid};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@freelancezone.ma',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // ── Clients ────────────────────────────────────────────────────────
        $clients = collect([
            ['name' => 'Youssef El Mansouri', 'email' => 'youssef@client.ma'],
            ['name' => 'Sara Alaoui',         'email' => 'sara@client.ma'],
            ['name' => 'Karim Benali',        'email' => 'karim@client.ma'],
        ])->map(fn($u) => User::create(array_merge($u, [
            'password' => Hash::make('password'),
            'role'     => 'client',
            'location' => 'Casablanca, Maroc',
            'is_active'=> true,
        ])));

        // ── Freelances ─────────────────────────────────────────────────────
        $freelancers = collect([
            ['name' => 'Abdel Rachid',  'email' => 'abdel@freelance.ma', 'skills' => ['Laravel','Vue.js','MySQL'], 'hourly_rate' => 35],
            ['name' => 'Fatima Zahra',  'email' => 'fatima@freelance.ma','skills' => ['React','Node.js','MongoDB'],'hourly_rate' => 40],
            ['name' => 'Omar Tazi',     'email' => 'omar@freelance.ma',  'skills' => ['Python','Django','AI/ML'],  'hourly_rate' => 50],
            ['name' => 'Nadia Chraibi', 'email' => 'nadia@freelance.ma', 'skills' => ['Figma','UI/UX','Adobe XD'],'hourly_rate' => 30],
        ])->map(fn($u) => User::create(array_merge($u, [
            'password'  => Hash::make('password'),
            'role'      => 'freelancer',
            'bio'       => 'Développeur expérimenté avec ' . rand(3,8) . ' ans d\'expérience.',
            'location'  => 'Marrakech, Maroc',
            'is_active' => true,
        ])));

        // ── Categories ─────────────────────────────────────────────────────
        $categories = collect([
            'Développement Web', 'Design & Créatif', 'Marketing Digital',
            'Rédaction & Traduction', 'Vidéo & Animation', 'Mobile',
            'Data & Analytics', 'Cybersécurité', 'IA & Machine Learning', 'Support IT',
        ])->map(fn($name) => Category::create([
            'name' => $name,
            'slug' => \Str::slug($name),
        ]));

        // ── Projects ───────────────────────────────────────────────────────
        $projectData = [
            ['Développement plateforme e-commerce Laravel + Vue.js', 'Développement Web',
             'Nous recherchons un développeur full-stack pour construire une plateforme e-commerce complète avec gestion des produits, panier, paiements Stripe, tableau de bord admin. Technologies : Laravel 11, Vue.js 3, MySQL, Redis.',
             500, 2000, ['Laravel','Vue.js','Stripe','Redis']],
            ['Application mobile React Native pour livraison', 'Mobile',
             'Développement d\'une app iOS/Android pour service de livraison à domicile. Fonctionnalités : géolocalisation en temps réel, notifications push, suivi des commandes, paiement intégré.',
             800, 3000, ['React Native','Firebase','Node.js']],
            ['Design UI/UX pour application SaaS', 'Design & Créatif',
             'Conception d\'une interface utilisateur moderne pour notre outil SaaS de gestion de projets. Livrables : wireframes, mockups haute fidélité, design system, prototype interactif Figma.',
             300, 1200, ['Figma','UI/UX','Design System']],
            ['Audit de sécurité et tests de pénétration', 'Cybersécurité',
             'Réalisation d\'un audit complet de sécurité de notre infrastructure web. Rapport détaillé avec recommandations, test OWASP Top 10, analyse des vulnérabilités.',
             1000, 4000, ['Pentest','OWASP','Kali Linux','Burp Suite']],
            ['Chatbot IA avec intégration GPT-4', 'IA & Machine Learning',
             'Développement d\'un assistant virtuel intelligent pour notre service client. Intégration API OpenAI, base de connaissances personnalisée, interface web moderne.',
             600, 2500, ['Python','OpenAI','FastAPI','Langchain']],
            ['Dashboard Analytics avec Tableau de bord', 'Data & Analytics',
             'Création d\'un tableau de bord analytique pour visualiser nos KPIs métier en temps réel. Connexion à plusieurs sources de données, graphiques interactifs.',
             400, 1500, ['Python','Power BI','SQL','ETL']],
        ];

        foreach ($projectData as $i => [$title, $catName, $desc, $min, $max, $skills]) {
            $cat = $categories->firstWhere('name', $catName);
            Project::create([
                'client_id'       => $clients->random()->id,
                'category_id'     => $cat->id,
                'title'           => $title,
                'description'     => $desc,
                'budget_min'      => $min,
                'budget_max'      => $max,
                'required_skills' => $skills,
                'status'          => 'open',
                'deadline'        => now()->addDays(rand(20, 60)),
                'is_featured'     => $i < 2,
            ]);
        }

        // ── Bids ───────────────────────────────────────────────────────────
        $projects = Project::all();
        foreach ($projects->take(3) as $project) {
            foreach ($freelancers->random(2) as $f) {
                Bid::create([
                    'project_id'    => $project->id,
                    'freelancer_id' => $f->id,
                    'amount'        => rand($project->budget_min, $project->budget_max),
                    'delivery_days' => rand(7, 30),
                    'cover_letter'  => 'Je suis très intéressé par ce projet. Fort de mon expérience en ' . implode(', ', $project->required_skills ?? ['développement']) . ', je peux livrer un travail de qualité dans les délais impartis.',
                    'status'        => 'pending',
                ]);
            }
        }

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info('Admin   : admin@freelancezone.ma / password');
        $this->command->info('Client  : youssef@client.ma / password');
        $this->command->info('Freelance: abdel@freelance.ma / password');
    }
}
