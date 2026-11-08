<?php

use App\Models\ParishEvent;
use App\Models\PastoralGroup;
use App\Models\Post;
use App\Models\Retreat;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\get;

beforeEach(function () {
    if (! extension_loaded('pdo_sqlite')) {
        $this->markTestSkipped('The local PHP runtime does not have pdo_sqlite; this test runs under Sail.');
    }

    Schema::dropIfExists('posts');
    Schema::dropIfExists('events');
    Schema::dropIfExists('pastoral_group_user');
    Schema::dropIfExists('pastoral_groups');
    Schema::dropIfExists('retreat_registrations');
    Schema::dropIfExists('retreats');
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->string('category')->nullable();
        $table->text('excerpt')->nullable();
        $table->longText('content');
        $table->string('cover_image')->nullable();
        $table->timestamp('published_at')->nullable();
        $table->unsignedBigInteger('author_id')->nullable();
        $table->timestamps();
    });

    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->string('category')->nullable();
        $table->text('description');
        $table->string('location')->nullable();
        $table->timestamp('starts_at');
        $table->timestamp('ends_at')->nullable();
        $table->string('cover_image')->nullable();
        $table->timestamp('published_at')->nullable();
        $table->unsignedBigInteger('author_id')->nullable();
        $table->timestamps();
    });

    Schema::create('pastoral_groups', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('type');
        $table->text('summary')->nullable();
        $table->longText('description');
        $table->string('meeting_schedule')->nullable();
        $table->string('meeting_location')->nullable();
        $table->string('public_contact')->nullable();
        $table->string('cover_image')->nullable();
        $table->timestamp('published_at')->nullable();
        $table->unsignedBigInteger('coordinator_id')->nullable();
        $table->unsignedBigInteger('author_id')->nullable();
        $table->timestamps();
    });

    Schema::create('retreats', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('summary')->nullable();
        $table->longText('description');
        $table->string('location')->nullable();
        $table->timestamp('starts_at');
        $table->timestamp('ends_at')->nullable();
        $table->timestamp('registration_opens_at')->nullable();
        $table->timestamp('registration_closes_at')->nullable();
        $table->unsignedInteger('capacity')->nullable();
        $table->string('public_contact')->nullable();
        $table->string('cover_image')->nullable();
        $table->timestamp('published_at')->nullable();
        $table->unsignedBigInteger('coordinator_id')->nullable();
        $table->unsignedBigInteger('author_id')->nullable();
        $table->timestamps();
    });

    Schema::create('retreat_registrations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('retreat_id');
        $table->string('name');
        $table->date('birth_date');
        $table->string('email');
        $table->string('phone');
        $table->string('community')->nullable();
        $table->string('mother_name')->nullable();
        $table->string('father_name')->nullable();
        $table->string('guardian_name')->nullable();
        $table->string('guardian_phone')->nullable();
        $table->string('emergency_contact')->nullable();
        $table->string('emergency_contact_name')->nullable();
        $table->string('emergency_contact_phone')->nullable();
        $table->text('allergies')->nullable();
        $table->text('medical_conditions')->nullable();
        $table->text('medications')->nullable();
        $table->text('dietary_restrictions')->nullable();
        $table->string('status');
        $table->text('internal_notes')->nullable();
        $table->timestamp('consented_at');
        $table->timestamp('health_data_consented_at')->nullable();
        $table->timestamps();
        $table->unique(['retreat_id', 'email']);
    });
});

test('the parish portal only displays content intended for the public', function () {
    Post::query()->create([
        'title' => 'Aviso publicado',
        'slug' => 'aviso-publicado',
        'excerpt' => 'Este conteúdo pode ser visto pela comunidade.',
        'content' => 'Conteúdo publicado.',
        'published_at' => now()->subMinute(),
    ]);
    ParishEvent::query()->create([
        'title' => 'Encontro da comunidade',
        'slug' => 'encontro-da-comunidade',
        'description' => 'Evento aberto para toda a paróquia.',
        'starts_at' => now()->addWeek(),
        'published_at' => now()->subMinute(),
    ]);
    PastoralGroup::query()->create([
        'name' => 'Pastoral de acolhida',
        'slug' => 'pastoral-de-acolhida',
        'type' => 'pastoral',
        'summary' => 'Uma equipe para receber a comunidade.',
        'description' => 'Conheça e participe da nossa missão de acolhida.',
        'published_at' => now()->subMinute(),
    ]);
    $retreat = Retreat::query()->create([
        'title' => 'Retiro de jovens',
        'slug' => 'retiro-de-jovens',
        'summary' => 'Um final de semana de fé e amizade.',
        'description' => 'Participe desta experiência com a juventude paroquial.',
        'starts_at' => now()->addMonth(),
        'capacity' => 40,
        'published_at' => now()->subMinute(),
    ]);

    expect($retreat->registrationStatus())->toBe(Retreat::REGISTRATION_OPEN);
    Post::query()->create([
        'title' => 'Rascunho interno',
        'slug' => 'rascunho-interno',
        'content' => 'Conteúdo interno.',
    ]);

    get('/')
        ->assertOk()
        ->assertSee('Uma comunidade de fé, encontro e missão.')
        ->assertSee('Aviso publicado')
        ->assertSee('Encontro da comunidade')
        ->assertDontSee('Rascunho interno');

    get('/noticias/aviso-publicado')
        ->assertOk()
        ->assertSee('Conteúdo publicado.');

    get('/noticias/rascunho-interno')->assertNotFound();

    get('/agenda/encontro-da-comunidade')
        ->assertOk()
        ->assertSee('Evento aberto para toda a paróquia.');

    get('/a-paroquia')
        ->assertOk()
        ->assertSee('Pastoral de acolhida');

    get('/a-paroquia/pastorais/pastoral-de-acolhida')
        ->assertOk()
        ->assertSee('Conheça e participe da nossa missão de acolhida.');

    get('/juventude')
        ->assertOk()
        ->assertSee('Retiro de jovens');

    get('/juventude/retiros/retiro-de-jovens')
        ->assertOk()
        ->assertSee('Participe desta experiência com a juventude paroquial.')
        ->assertSee('Faça sua inscrição')
        ->assertDontSee('Inscrições encerradas');

    $this->post('/juventude/retiros/retiro-de-jovens/inscricao', [
        'name' => 'Participante menor sem responsável',
        'birth_date' => '2010-05-10',
        'email' => 'menor@example.com',
        'phone' => '(91) 97777-7777',
        'emergency_contact_name' => 'Contato de emergência',
        'emergency_contact_phone' => '(91) 96666-6666',
        'consent' => '1',
        'health_data_consent' => '1',
    ])->assertSessionHasErrors(['guardian_name', 'guardian_phone']);

    $this->post('/juventude/retiros/retiro-de-jovens/inscricao', [
        'name' => 'Jovem Participante',
        'birth_date' => '2010-05-10',
        'email' => 'jovem@example.com',
        'phone' => '(91) 99999-9999',
        'mother_name' => 'Maria Responsável',
        'guardian_name' => 'Maria Responsável',
        'guardian_phone' => '(91) 98888-8888',
        'emergency_contact_name' => 'Maria Responsável',
        'emergency_contact_phone' => '(91) 98888-8888',
        'allergies' => 'Alergia a amendoim',
        'consent' => '1',
        'health_data_consent' => '1',
    ])->assertSessionHas('registration_success');

    $this->assertDatabaseHas('retreat_registrations', [
        'email' => 'jovem@example.com',
        'status' => 'pending',
        'guardian_name' => 'Maria Responsável',
    ]);

    $registration = \App\Models\RetreatRegistration::where('email', 'jovem@example.com')->firstOrFail();
    expect($registration->allergies)->toBe('Alergia a amendoim')
        ->and(DB::table('retreat_registrations')->where('id', $registration->id)->value('allergies'))->not->toBe('Alergia a amendoim');
});
