# Projeto de Geração de Bilhetes de Loteria

Bem-vindo ao projeto de geração e validação de bilhetes de loteria! 
Este README irá guiá-lo sobre como configurar e executar o projeto em seu ambiente local, bem como como executar os testes para garantir que tudo esteja funcionando corretamente.

##  Iniciando o Projeto

Para começar a trabalhar com este projeto, siga estas etapas simples:

### 1. Clonar o Repositório

Primeiro, clone o repositório para sua máquina local:

```bash
    git clone https://github.com/seu-usuario/seu-repositorio.git
    cd seu-repositorio
```
### 2 Rode os comandos abaixo em seu ambiente

Os comandos abaixo já configuram o banco de dados e a criação das tabelas de banco de dados.

```bash
    docker-compose up -d --build
```

Caso haja  algum problema ao carregar a vendor após o comando utilize

```bash
    sudo docker exec -it teste-web-1 composer install
```

### 3. Executar testes
Para executar os testes do projeto rode o comando abaixo.

```bash
    sudo docker exec -it teste-web-1 vendor/bin/phpunit
```

### 4. Interface

Para gerar um número premiado acesse a url 

```bash
    http://localhost:8080/generate-ticket
```

as demais rotas têm interface visual
a responsavel por gerar bilhetes do tripulante

```bash
    http://localhost:8080/generate-tickets.html
```

a responsavel por gerar listar os bilhetes e informar se foi sorteado

```bash
    http://localhost:8080/check-tickets.html
```