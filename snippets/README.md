
# Snippets

Comment ajouter un snippet dans VSCode ?

- Cliquer sur l'engrenage des paramètres
- Cliquer sur "User Snippets"
- Sélectionner le langage dans lequel on veut ajouter le snippet

Mon custom snippets est trouvable ici :

__Le prefixe est la valeur à écrire dans VSCode pour trigger le snippet__

## PHP / WordPress
### Var_dump (Trigger : "dump")
Créer automatiquement un dump englober par des balises `<pre>` et
mets automatique le cursor dans le dump.

```php
	"Create var_dump": {
		"prefix": "dump",
		"body": [
			"echo '<pre>';",
			"var_dump($1);",
			"echo '</pre>';"
		],
		"description": "var_dump with pre tags"
	},
```

### Print_r (Trigger : "printr")
Créer automatiquement un print_r englober par des balises `<pre>` et
mets automatique le cursor dans le print_r.
```php
	"Create print_r": {
		"prefix": "printr",
		"body": [
			"echo '<pre>';",
			"print_r($1);",
			"echo '</pre>';"
		],
		"description": "print_r with pre tags"
	},
```

### Fermer et ouvrir PHP rapidement (Trigger : "pcpo")
Ferme la la balise PHP, et la re-ouvre automatiquement. <br>
Puis insère le curseur entre les deux balises.
```php
	"Create pc_po": {
		"prefix": "pcpo",
		"body": [
			"?>",
			"$1",
			"<?php"
		],
		"description": "print_r with pre tags"
	},
```
