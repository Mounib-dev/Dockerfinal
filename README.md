Le Dockerfile contient le code nécessaire pour construire l'image Docker

Ensuite on ajoute le compose.yaml pour lancer les services web (qui correspond à une image Apache avec PHP) qu'on construit depuis le Dockerfile en le spécficiant dans le fichier compose)

On fait en sorte d'avoir un volume dans le service db pour avoir ensuite des données dedans.

Pour que les deux services puissent communiquer entre eux, je définis un réseau au début de mon fichier compose où je le nomme et le mets de type bridge => Ensuite dans chaque service, je dois définir networks avec le même nom du network global défini pour que les deux services arrivent ensuite à communiquer entre eux.
