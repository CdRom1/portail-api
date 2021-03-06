<?php

use Illuminate\Database\Seeder;
use App\Models\Asso;
use App\Models\AssoType;

class AssosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  	    $assos = [
			[
				'login'			=> 'bde',
				'shortname'		=> 'BDE',
				'name'			=> 'Bureau des Etudiants',
				'description'	=> <<<DESC
Le bureau des étudiants de l'UTC (BDE-UTC) est une association 1901 qui a pour objet l'animation et la structuration de la vie étudiante de l'UTC de manière saine, responsable et constructive.

Dans ce cadre le BDE-UTC administre la Maison des Étudiants (MDE) dans le cadre de la Convention d'Occupation de la MDE signée chaque année avec l'UTC. Depuis début septembre, le BDE et ses commissions s'attachent à supprimer les nuisances sonores aux abords des bâtiments.

Le BDE est ouvert et assure des permanences !

Le nouveau bureau se situe dans la MDE, au bâtiment E, en E101.

Nous t'accueillons :
Du lundi au vendredi de 08:00 à 18:30 (le planning détaillé est disponible sur le wiki des assos http://assos.utc.fr/wiki)

N'hésite pas à venir nous poser tes questions!

Viens, on est bien !
DESC
,
				'type_asso_id'  => '1901',
			],
			[
				'login'			=> 'pae',
				'shortname'		=> 'PAE',
				'name' 			=> 'Pôle Artistique et Événementiel',
				'description'	=> <<<DESC
Tout savoir sur l'art, la culture et les assos événementielles à l'UTC, c'est ici que ca se passe !

Le PAE est fier d'être impliqué dans l'UTCéenne, l'IF, les Estus, les concerts de Strava, Larsen, Ocata... Ils sont trop pour un simple paragraphe !

Trésorerie, administratif, communication, le PAE aide les assos dans leur gestion pour voir leur projet aboutir.
DESC
,
				'type_asso_id'  => '1901',
				'parent_login'  => 'bde',
			],
			[
				'login'			=> 'psec',
				'shortname'		=> 'PSEC',
				'name'			=> 'Pôle Solidarité Et Citoyenneté',
				'description'	=> <<<DESC
Le Pôle Solidarité et Citoyenneté est là pour soutenir, accompagner et coordonner les associations à caractère solidaire ou citoyenne de l'UTC.

Le pôle est la personne à contacter si tu as envie de t'investir dans la solidarité internationale, le développement durable, la défense des droits de l'homme, le combat contre les discriminations de toutes sortes, mais que tu ne sais pas quelle asso rejoindre. De même, si tu apportes ton propre projet associatif, n'hésite pas à nous contacter pour le lancer dans de bonnes conditions.
DESC
,
				'type_asso_id'	=> '1901',
				'parent_login'	=> 'bde',
			],
			[
				'login'			=> 'pte',
				'shortname'		=> 'PTE',
				'name'			=> 'Pôle Technologie et Entreprenariat',
				'description'	=> <<<DESC
Le PTE est l'un des quatre pôles associatifs de l'utc...

Ok, mais les pôles, c'est quoi?

Eh bien...
Le but des pôles est de rassembler les associations par thématique, afin de promouvoir la coopération inter-assos et aider chaque asso dans leurs projets. L'objectif des pôles est de soutenir, maintenir et développer la vie associative à l'UTC.
Ainsi, les pôles sont la charnière entre les associations et le Bureau Des Etudiants (BDE).


Le PTE regroupe les assos en rapport avec le thème des technologies et de l'entreprenariat.

Nous avons la charge de la répartition des subventations accodées par le BDE, l'écoute des attentes des assos, la communication de leurs activités, le support logistique... Cela passe par exemple, par l'organisation d'une semaine des pôles, la proposition d'un local atelier pour les associations technologiques, ou la représentation des associations lors de grands évenement utcéens.

Hormis cette activité de soutien, le PTE organise des conférences conviviales, autour des thèmes soutenus par le pôle (la technologie, le monde professonnel, l'innovation), qui seront proposées directement à destination des élèves-ingénieurs de l'UTC, toutes branches confondues.

Si vous êtes désireux d'apporter votre aide, des idées, au pôle ou à ses associations, ou encore si vous êtes porteur d'un projet associatif s'inscrivant dans notre thématique, n'hésitez pas à nous contacter!
DESC
,
				'type_asso_id'  => '1901',
				'parent_login'  => 'bde',
			],
			[
				'login'			=> 'pvdc',
				'shortname'		=> 'PVDC',
				'name'			=> 'Pôle Vie de Campus',
				'description'	=> <<<DESC
Le PVDC c'est de la diversité, c'est des assos de service en folie, et un bureau uni pour que tout ça fonctionne bien !!

Chaque membre du bureau est référent de plusieurs assos pour les aider, les soutenir et communiquer tout ce qu'il faut.

Le pôle regroupe différents types d'asso: jeux, divertissements, activités culturelles, services en folie, promotion de la vie étudiante ...

En début de semestre, les référents aident les assos à se lancer et les suivent tout au long du semestre.

Bien sûr; si tu veux créer une nouvelle asso qui rejoint l'un de ces thèmes, contacte ton pôle qui répondra à toutes tes questions !
DESC
,
				'type_asso_id'  => '1901',
				'parent_login'  => 'bde',
			],
			[
				'login'			=> env('APP_ASSO', 'simde'),
				'shortname'		=> 'SiMDE',
				'name'			=> 'Service Informatique de la MDE (Maison Des Etudiants)',
				'description'	=> <<<DESC
Le SiMDE est chargé de la mise en place :
	- des ressource informatiques au sein de la MDE
	- du serveur des associations Phoenix, hébergeant mails, sites web, fichiers...
	- de la gestion du site assos.utc.fr
DESC
,
				'type_asso_id'  => 'commission',
				'parent_login'  => 'bde',
			],
			[
				'login'         => 'integ',
				'shortname'          => 'Integ\'UTC',
				'name'          => 'Integration UTC',
				'description'   => <<<DESC
L'integ, c'est l'association qui s'occupe de l'accueil et de l'intégration des nouveaux étudiants de l'UTC.

Chaque année, c'est reparti pour une dizaine de jours de festivités inoubliables. Pendant la première partie, quatre familles représentées par les quatre énormes clans qui s'entretuent pour la plus prestigieuse des distinctions, le fameux, l'ultime, l'inoubliable : le BIDET d'OR.
La conclusion : un weekend magique qui allie l'ambiance d'un festival musical (sonorité reggae, pop, rock folk, électro) aux animations d'un parc d'attractions (kayak, jeux gonflables, kermesse, animation à sensations)

L'integ, c'est aussi et surtout l'asso qui te permet de te sentir chez toi en 10 jours, de considérer les associations étudiantes comme ta famille, la MDE comme ton toit, l'UTC comme ta maison.

Tu es futur nouvö à l'UTC? N'hésite pas à faire un tour sur nos forums et tiens toi prêt pour les 10 plus beaux jours de ta vie en septembre prochain !
Du 30 août au 10 Septembre viens vibrer avec nous pour gagner le Bidet d'or...

Ensemble, rendons cet événement inoubliable
DESC
,
				'type_asso_id'  => 'commission',
				'parent_login'  => 'pvdc',
			],
        ];

        foreach ($assos as $asso) {
			Asso::create([
				'login' => $asso['login'],
				'shortname' => $asso['shortname'],
				'name' => $asso['name'],
				'description' => $asso['description'],
				'type_asso_id' => isset($asso['type_asso_id']) ? AssoType::where('name', $asso['type_asso_id'])->first()->id : null,
				'parent_id' => isset($asso['parent_login']) ? Asso::where('login', $asso['parent_login'])->first()->id : null,
			]);
      	}
    }
}
