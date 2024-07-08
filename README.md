# APIs de Teste em Yii2

## Instalação

1. Clone o repositório
2. Na raiz do projeto, crie o arquivo `.env` com base no arquivo `.env.example`
3. Na raiz do projeto, vá para o diretorio do docker: `cd docker_conf`
4. Crie a rede do docker `docker network create --subnet=172.18.0.0/16 php-apache`
5. Execute o comando: `docker-compose up -d --build`
6. Com o docker rodando, acesse o container do php: `docker exec -it docker_conf-php71-1 bash`
7. Dentro do container, execute o comando: `composer install`
8. Acesse o Adminer para criar o banco de dados: `http://localhost:8080` (servidor: `db` usuário: `root`, senha: `1234`, banco: `yii2_test`)
9. Execute o comando: `php yii migrate` para criar as tabelas no banco de dados

### Criar usuário
```php yii user/create <login> <name> <password>```

### Endpoint de Autenticação
`GET /auth/login` - Logar-se (retorna o token)

Exemplo de requisição:
```json
{
    "login": "admin",
    "password": "admin"
}
```

### Endpoints GET (Necessário token)
1. `GET /client/index?page=1` - Lista a página 1 de clientes
2. `GET /product/index?cpf=99999999999` - Lista todos os produtos de um determinado cliente

### Endpoints POST (Necessário token)
1. `POST /client/create-client` - Cria um cliente

Exemplo de requisição:
```json
{
   "name": "Client Name",
   "cpf": "12345678900", only numbers
   "cep": "12345678", only numbers
   "address": "Client Address",
   "number": "Client Number",
   "city": "Client City",
   "state": "Client State",
   "complement": "Client Complement",
   "photo": "http://www.example.com/photo.jpg",
   "sex": "M"
}
```
2. `POST /product/create-product` - Cria um produto

Exemplo de requisição:
```json
{
   "name": "Product Name",
   "price": 100.00,
   "cpf": "12345678900", only numbers
   "photo": "http://www.example.com/photo.jpg"
}
```

**Obs.:** O token deve ser passado no header `Authorization` Auth Type `Bearer Token` na requisição# teste-yii2
