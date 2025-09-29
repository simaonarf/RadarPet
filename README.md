## RadarPet

O RadarPet é uma plataforma estruturada para a divulgação de pets desaparecidos. A ferramenta procura solucionar a desorganização das redes sociais, que são pouco eficientes para essa finalidade. O objetivo é criar um ambiente confiável e colaborativo, agilizando a comunicação com a comunidade. Com isso, busca-se aumentar as chances de reencontro entre os tutores e seus animais.

### Dependências

- Docker
- Docker Compose

### To run

#### Clone Repository

```
$ git clone git@github.com:simaonarf/RadarPet.git
$ cd RadarPet
```

#### Define the env variables

```
$ cp .env.example .env
```

#### Install the dependencies

```
$ ./run composer install
```

#### Up the containers

```
$ docker compose up -d
```

ou

```
$ ./run up -d
```
