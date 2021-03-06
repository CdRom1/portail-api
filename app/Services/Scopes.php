<?php

namespace App\Services;

use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;

/**
 * Cette classe permet de récupérer des informations concernant un membre de l'UTC
 */
class Scopes {
	/*
	 * Liste des scopes en fonction des routes
	 *   - Définition des scopes:
	 *   	portée + "-" + verbe + "-" + categorie + (pour chaque sous-catégorie: '-' + sous-catégorie)
	 *   	ex: user-get-user user-get-user-assos user-get-user-assos-followed
	 *
	 *   - Définition de la portée des scopes:
	 *     + user :    user_credential => nécessite que l'application soit connecté à un utilisateur
	 *     + client :  client_credential => nécessite que l'application est les droits d'application indépendante d'un utilisateur
	 *
	 *   - Définition du verbe:
	 *     + manage:  gestion de la ressource entière
	 *       + get :  récupération des informations en lecture seule
	 *       + set :  posibilité d'écrire et modifier les données
	 *         + create:  créer une donnée associée
	 *         + edit:    modifier une donnée
	 *         + remove:  supprimer une donnée
	 */
	protected $scopes;

	public function __construct() {
		$this->scopes = config('scopes');
	}

	/**
	 * Génère le scope et les hérédités
	 * @param  string $prefix
	 * @param  array $subScopes
	 * @return array
	 */
	private function generate(string $before, array $subScopes) {
		$scopes = [];

		foreach ($subScopes as $name => $data) {
			$prefix = $before.'-'.$name;

			if (isset($data['scopes']))
				$scopes = array_merge($scopes, $this->generate($prefix, $data['scopes']));

			$scopes[$prefix] = $data['description'];
		}

		return $scopes;
	}

	/**
	 * Renvoie tous les scopes et les hérédités
	 * @param  string $prefix
	 * @param  array $subScopes
	 * @return array
	 */
	public function all() {
		$scopes = [];

		foreach ($this->scopes as $type => $categories) {
			foreach ($categories as $name => $categorie) {
				foreach ($categorie['verbs'] as $verb => $data) {
					$prefix = $type.'-'.$verb.'-'.$name;

					if (isset($data['scopes']))
						$scopes = array_merge($scopes, $this->generate($prefix, $data['scopes']));

					$scopes[$prefix] = $data['description'];
				}
			}
		}

		return $scopes;
	}

	/**
	 * Renvoie les scopes (doivent exister !) avec leur description par catégorie
	 * @param  array $scopes
	 * @return array
	 */
	public function getAllByCategories() {
		$categories = [];
		foreach ($this->all() as $scope => $description) {
			$elements = explode('-', $scope);

			if (!isset($categories[$elements[2]]) && !isset($categories[$elements[2]]['scopes'])) {
				$categorie = $this->scopes[$elements[0]][$elements[2]];

				$categories[$elements[2]] = [
					'description' => $categorie['description'],
					'scopes' => [
						$scope => $description,
					]
 				];
			}
			else
				$categories[$elements[2]]['scopes'][$scope] = $description;
		}

		return $categories;
	}

	/**
	 * Donne le verbe qui suit par héridité montante ou descendante
	 * @param  string  $verb
	 * @param  boolean $up
	 * @return array        liste des verbes à suivre
	 */
	private function nextVerbs(string $verb, $up = false) {
		if ($up) {
			switch ($verb) {
				case 'get':
				case 'set':
				case 'remove':
					return ['manage'];
					break;

				case 'create':
				case 'edit':
					return ['set'];
					break;

				default:
					return [];
			}
		}
		else {
			switch ($verb) {
				case 'manage':
					return ['get', 'set'];
					break;

				case 'set':
					return ['create', 'edit'];
					break;

				default:
					return [];
			}
		}
	}

	/**
	 * Recherche le scope existant (qui doit exister) et sa descendance
	 * @param  string $scope
	 * @return array
	 */
	private function find(string $scope) {
		$elements = explode('-', $scope);

		if (count($elements) < 3)
			throw new \Exception('Le scope '.$scope.' est incorrect et doit au moins posséder un système d\'authentification, un verbe et une catégorie');

		if (!isset($this->scopes[$elements[0]][$elements[2]]['verbs'][$elements[1]]))
			return [];

		$current = $this->scopes[$elements[0]][$elements[2]]['verbs'][$elements[1]];
		for ($i = 3; $i < count($elements); $i++) {
			if (!isset($current['scopes'][$elements[$i]]))
				return [];

			$current = $current['scopes'][$elements[$i]];
		}

		if ($current === [] || !isset($current['description']))
			throw new \Exception('Le scope '.$scope.' est mal défini dans le fichier de config');
		else
			return [
				$scope => $current,
			];
	}

	/**
	 * Renvoie le scope (doit exister !) avec sa description
	 * @param  string $scope
	 * @return array      scope => description
	 */
	public function get(string $scope) {
		$current = $this->find($scope);

		if ($current === [] || $current === null)
			return [];

		return [
			$scope => $current[$scope]['description'],
		];
	}

	/**
	 * Renvoie les scopes (doivent exister !) avec leur description par catégorie
	 * @param  array $scopes
	 * @return array
	 */
	public function getByCategories(array $scopes) {
		$categories = [];

		if ($scopes === [] || $scopes === null)
			return [];

		foreach ($scopes as $scope) {
			$elements = explode('-', $scope);

			if (!isset($middleware))
				$middleware = $elements[0];
			elseif ($middleware !== $elements[0])
				throw new \Exception('Les scopes ne sont pas définis avec les mêmes types d\'authentification !'); // Des scopes commençant par c- et u-

			$current = $this->get($scope);

			if ($current === [])
				throw new \Exception('Le scope '.$scope.' n\'existe pas !');

			if (!isset($categories[$elements[2]]) && !isset($categories[$elements[2]]['scopes'])) {
				$categorie = $this->scopes[$middleware][$elements[2]];

				$categories[$elements[2]] = [
					'description' => $categorie['description'],
					'icon' => $categorie['icon'],
					'scopes' => [
						$current[$scope]
					]
 				];
			}
			else
				array_push($categories[$elements[2]]['scopes'], $current[$scope]);
		}

		return $categories;
	}

	/**
	 * Renvoie le scope et ses parents ou ses hérédités (prend en compte l'héridité des verbes)
	 *
	 * @param string $scope
	 * @param bool $down Permet de spécifier dans quel sens de l'héridité à générer
	 * @return array
	 */
	public function getRelatives(string $scope = null, $up = false) {
		if ($scope === null)
			return $this->all();

		$current = $this->find($scope);

		if ($current === [] || $current === null)
			return [];

		$scopes = [
			$scope => $current[$scope]['description'],
		];

		$elements = explode('-', $scope);

		if ($up) {
			for ($i = count($elements) - 1; $i > 2; $i--) {
				array_pop($elements);
				$scopes = array_merge($scopes, $this->getRelatives(implode('-', $elements), $up));
			}

			$elements = explode('-', $scope);
		}
		else if (isset($current[$scope]['scopes'])) {
			$scopes = array_merge($scopes, $this->generate($scope, $current[$scope]['scopes']));
		}

		$nextVerbs = $this->nextVerbs($elements[1], $up);

		if ($nextVerbs !== []) {
			foreach($nextVerbs as $nextVerb) {
				$elements[1] = $nextVerb;
				$scopes = array_merge($scopes, $this->getRelatives(implode('-', $elements), $up));
			}
		}

		return $scopes;
	}

	/**
	 * Retourne la liste des scopes et des ses parents (prise en compte de l'héridité des verbes)
	 *
	 * @param array $scopes
	 * @return array
	 */
	public function getMatchingScopes(array $scopes = [], bool $checkMiddleware = true, string $middleware = null) {
		if ($scopes === [] || $scopes === null)
			throw new \Exception('Il est nécessaire de définir au moins un scope ou d\'utiliser matchAny([bool $canBeUser = true, bool $canBeClient = true])');

		$matchingScopes = [];

		foreach ($scopes as $scope) {
			if ($scope === null)
				throw new \Exception('Il est nécessaire de définir au moins un scope ou d\'utiliser matchAny([bool $canBeUser = true, bool $canBeClient = true])');

			$elements = explode('-', $scope);

			if (!isset($middleware))
				$middleware = $elements[0];
			elseif ($middleware !== $elements[0] && $checkMiddleware)
				throw new \Exception('Les scopes ne sont pas définis avec les mêmes types d\'authentification !'); // Des scopes commençant par c- et u-

			$current = $this->getRelatives($scope, true);

			if ($current === [])
				throw new \Exception('Le scope '.$scope.' n\'existe pas !');

			$matchingScopes = array_merge($matchingScopes, $current);
		}

		return array_keys($matchingScopes);
	}

	/**
	 * Retourne les Middleware d'authentification
	 *
	 * @param boolean $userMustBeConnected
	 * @return array
	 */
	private function matchAny(array $userScopes = [], array $clientScopes = [], bool $matchOne = true) {
		if (count($userScopes) > 0) {
			if (count($clientScopes) > 0)
				$middleware = 'auth.any:'.implode(',', [($matchOne ? '1' : '0'), implode('|', $userScopes), implode('|', $clientScopes)]);
			else
				$middleware = 'auth.user:'.implode(',', [($matchOne ? '1' : '0'), implode('|', $userScopes)]);
		}
		else if (count($clientScopes) > 0)
			$middleware = 'auth.client:'.implode(',', [($matchOne ? '1' : '0'), implode('|', $clientScopes)]);
		else
			return [];

		return [
			$middleware,
			'auth.check',
		];
	}

	/**
	 * Retourne les Middleware d'authentification pour tout client connecté à un utilisateur
	 *
	 * @return array
	 */
	public function matchAnyUser() {
		return [
			'auth.user',
			'auth.check'
		];
	}

	/**
	 * Retourne les Middleware d'authentification pour tout client non connecté à un utilisateur
	 *
	 * @return array
	 */
	public function matchAnyClient() {
		return [
			'auth.client',
			'auth.check'
		];
	}

	/**
	 * Retourne les Middleware d'authentification pour tout client connecté ou non à un utilisateur
	 *
	 * @return array
	 */
	public function matchAnyUserOrClient() {
		return [
			'auth.any',
			'auth.check'
		];
	}

	/**
	 * Retourne les Middleware à utiliser pour accéder à une route en matchant le scope ou les scopes
	 * @param  string/array $scopes  Liste des scopes ou des scopes user/client à avoir si on est user/client
	 * @param  array $scopes2		 Liste des scopes client/user à avoir
	 * @return array
	 */
	public function match($scopes, array $scopes2 = []) {
		if (is_array($scopes))
			return $this->matchAll($scopes, $scopes2);
		else {
			array_push($scopes2, $scopes);

			return $this->matchOne($scopes2);
		}
	}

	/**
	 * Retourne les Middleware à utiliser pour accéder à une route en matchant au moins un scope parmi la liste
	 *
	 * @param string/array $scopes
	 * @return array
	 */
	public function matchOne($scopes = [], $scopes2 = []) {
		$scopes = !is_array($scopes) ? [$scopes] : $scopes;
		$scopes2 = !is_array($scopes2) ? [$scopes2] : $scopes2;

		if (count($scopes) == 0)
			throw new \Exception('Il est nécessaire de définir au moins un scope ou d\'utiliser matchAny([bool $canBeUser = true, bool $canBeClient = true])');

		if (explode('-', $scopes[0])[0] === 'user')
			return $this->matchAny($scopes, $scopes2);
		else
			return $this->matchAny($scopes2, $scopes);


		return $this->matchAny($scopes, $scopes2);

		return $this->matchAny($middleware !== 'client', $middleware !== 'user', $scopeList);
	}

	/**
	 * Retourne les Middleware à utiliser pour accéder à une route en matchant tous les scopes ou leurs parents de la liste
	 *
	 * @param string/array $scopes
	 * @return array
	 */
	public function matchAll(array $scopes = [], array $scopes2 = []) {
		if (count($scopes) == 0)
			throw new \Exception('Il est nécessaire de définir au moins un scope ou d\'utiliser matchAny([bool $canBeUser = true, bool $canBeClient = true])');

		if (explode('-', $scopes[0])[0] === 'user')
			return $this->matchAny($scopes, $scopes2, false);
		else
			return $this->matchAny($scopes2, $scopes, false);
	}

	/**
	 * Retourne si le token est du type User
	 * @param  Request $request
	 * @return boolean
	 */
	public function isUserToken(Request $request) {
		return $request->user() !== null;
	}

	/**
	 * Retourne si le token est du type User
	 * @param  Request $request
	 * @return boolean
	 */
	public function isClientToken(Request $request) {
		return $request->user() === null;
	}

	public function isUserOrClientToken(Request $request) {
		if ($request->user() === null) {
			$bearerToken = $request->bearerToken();
			$tokenId = (new Parser())->parse($bearerToken)->getHeader('jti');
			$token = Token::find($tokenId);

			if ($token !== null)
				return false;
		}

		return true;
	}

	protected function getToken(Request $request) {
		if ($request->user() === null) {
			$bearerToken = $request->bearerToken();
			$tokenId = (new Parser())->parse($bearerToken)->getHeader('jti');

			return Token::find($tokenId);
		}
		else
			return $request->user()->token();
	}

	/**
	 * Retourne les Middleware à utiliser pour accéder à une route en matchant le scope ou les scopes
	 * @param  string/array $scopes
	 * @return boolean
	 */
	public function has(Request $request, $scopes) {
		return is_array($scopes) ? $this->hasAll($request, $scopes) : $this->hasOne($request, [$scopes]);
	}

	/**
	 * Retourne si on peut accéder à une route en matchant au moins un scope parmi la liste
	 *
	 * @param string/array $scopes
	 * @return boolean
	 */
	public function hasOne(Request $request, $scopes = []) {
		if (is_array($scopes))
			$scopes = $this->getMatchingScopes($scopes);
		else
			$scopes = $this->getMatchingScopes([$scopes]);

		foreach ($this->getToken($request)->scopes as $scope) {
			if (in_array($scope, $scopes))
				return true;
		}

		return false;
	}

	/**
	 * Retourne si on peut accéder à une route en matchant tous les scopes parmi la liste
	 *
	 * @param string/array $scopes
	 * @return boolean
	 */
	public function hasAll(Request $request, array $scopes = []) {
		if (is_array($scopes))
			$scopes = $this->getMatchingScopes($scopes);
		else
			$scopes = $this->getMatchingScopes([$scopes]);

		$token = $request->token() ?? $request->user()->token();

		foreach ($this->getToken($request)->scopes as $scope) {
			if (!in_array($scope, $scopes))
				return false;
		}

		return true;
	}

	/**
	 * Génère une exception si les scopes ne sont correspondent pas au bon type d'authentification
	 * @param  array  $scopes
	 * @param  string $grantType
	 */
	public function checkScopesForGrantType(array $scopes, string $grantType = null) {
		if ($scopes === [] || $scopes === null)
			return;

		foreach ($scopes as $scope) {
			$elements = explode('-', $scope);

			if (!isset($middleware))
				$middleware = $elements[0];
			elseif ($middleware !== $elements[0])
				throw new \Exception('Les scopes ne sont pas définis avec les mêmes types d\'authentification !'); // Des scopes commençant par c- et u-
		}

		if ($middleware === 'client' && $grantType !== 'client_credentials' || $grantType === 'client_credentials' && $middleware !== 'client')
			throw new \Exception('Les scopes ne sont pas définis pour le bon type d\'authentification !'); // Des scopes commençant par c- et u-
	}
}
