## sistema-carne-api
Este projeto consiste em uma API backend desenvolvida em PHP. Utiliza MySQL como sistema de banco de dados, com Docker e Docker Compose para simplificar a configuração e a execução do ambiente de desenvolvimento.

## Requisitos

- Docker
- Docker Compose

## Configuração do Ambiente

### Clonando o Repositório

```bash
git clone https://github.com/Deividdasilva/sistema-carne-api.git
cd sistema-carne-api
```

### Iniciando os Containers

Utilize o Docker Compose para iniciar os containers:

```bash
docker-compose up -d
```

### Acessando a Aplicação

- **API Backend**: [http://localhost:8000](http://localhost:8000)
- **Banco**: [http://localhost:8080](http://localhost:8080)
- **Endpoint para criação de carnês**: [POST][http://localhost:8000/carne](http://localhost:8000/carne)
- **Endpoint para consulta de parcelas**: [GET][http://localhost:8000/carne](http://localhost:8000/carne/{id})


### Estrutura do Projeto
- **sistema-carnes/src: Contém o código-fonte do backend.**
- **Database: Configurações e scripts de conexão com o banco de dados.**
- **Models: Modelos usados para a abstração do banco de dados.**
- **Services: Lógica de negócios.**
- **Controllers: Controladores para manipular as requisições e respostas HTTP.**
