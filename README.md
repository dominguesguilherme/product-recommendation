# Product Recommendation
O objetivo desse projeto é fornecer uma lista de produtos recomendados baseado no histórico de pedidos.

## Decisões Técnicas
### Linguagem e Framework

Optei por desenvolver este projeto utilizando PHP 8 com PHP FPM e o framework Symfony. Aqui está o raciocínio por trás dessa escolha:

* **PHP 8**: Escolhi o PHP 8 por ser uma das versões mais recentes e também pela compatibilidade com as versões das bibliotecas que eu tenho familiaridade

* **PHP FPM**: Utilizei o PHP FPM para gerenciar os processos PHP de forma eficiente e escalável.

* **Symfony:** Optei pelo Symfony devido a minha familiaridade com o framework e a sua estrutura robusta. Symfony oferece uma ampla gama de componentes e bibliotecas que facilitam o desenvolvimento de aplicações complexas. Além disso, Symfony promove boas práticas de desenvolvimento e possui uma comunidade ativa que fornece suporte contínuo.

#### Por que UUID?

O uso de [UUID](https://pt.wikipedia.org/wiki/Identificador_%C3%BAnico_universal) traz benefícios como a integridade das entidades do sistema. Com IDs automáticos, entidades permanecem inválidas até serem persistidas no banco de dados, complicando os testes. Esse cenário exige a persistência no banco, tornando os testes mais lentos devido ao I/O.

Com UUID, esses problemas são evitados, pois o UUID é sempre único. Podemos adicioná-lo ao construtor da entidade, garantindo que os objetos tenham um estado válido desde a criação.

#### Gostaria de ter feito e não fiz

* Como é um cenário hipotético, estou assumindo que os produtos já existão na aplicação (seja consumidos por outro serviço ou pelo DB do mesmo), então não foram tratados os cenários de borda onde o produto não existe.

* Pra simplificar, não foram feitas as tratativas dos cenários de bordas em relação as respostas da aplicação (404, 400). Eu poderia ter implementado um middleware pra tratar diferentes erros e responder uma resposta, gosto de trabalhar as respostas de erro baseado na [RFC 7807](https://datatracker.ietf.org/doc/html/rfc7807)

* Pra simplificar, a modelagem do banco foi feita utilizando o Doctrine ORM, isso faz com que o dominio esteja acoplado com o framework, o ideal é que o mapeamento tivesse sido feita pra manter a coerência do dominio. Um exemplo, para garantir um mapeamento correto, foi necessário que o `OrderItem` tivesse um atributo `Order` pra gerar a relação `OneTOMany`.

### Arquitetura

Pensando no cenário de recomendação de produtos, vejo que este é um projeto com grande potencial para evoluir e se tornar muito complexo, especialmente em termos de requisitos de negócio. Por isso, optei por utilizar um modelo arquitetural onde o domínio é o centro, conhecido como Clean Architecture, e consequentemente, Domain-Driven Design (DDD).

Também foram trabalhados alguns temas importantes que fazem sentido aqui:

* **Design Patterns:** Utilizo padrões de projeto para resolver problemas comuns de design de forma eficiente e reutilizável, facilitando a manutenção e a escalabilidade do código.

* **S.O.L.I.D:** Aplicar os princípios S.O.L.I.D ajuda a criar um código mais modular, flexível e de fácil manutenção, promovendo boas práticas de programação orientada a objetos.

* **Test-Driven Development (TDD):** Adotar TDD garante que o código seja testável e confiável desde o início, ajudando a identificar e corrigir bugs mais cedo no processo de desenvolvimento e melhorando a qualidade geral do software.

Essas abordagens garantem que o projeto seja bem estruturado, sustentável e preparado para futuras evoluções e complexidades.

***Diagrama simples da soulução:***

![image](./diagrama.png)



## Classe `HistoricalProductRecommender`
Esse serviço fica responsável por realizar as recomendações de produtos a partir de um hitórico de pedidos.

### Descrição do Algoritmo
A classe `HistoricalProductRecommender` implementa um algoritmo simples para recomendar produtos com base no histórico de pedidos. A decisão de manter o algoritmo simples foi um trade-off para facilitar a implementação e manutenção, mesmo que isso implique em algumas limitações de desempenho.

### Funcionamento do Algoritmo
O algoritmo percorre todos os pedidos e conta a frequência com que cada produto é comprado junto com o produto alvo. Em seguida, ordena esses produtos por frequência e seleciona os principais produtos, de acordo com o limite especificado.

### Performance e Complexidade
O algoritmo é relativamente performático para conjuntos de dados pequenos a médios, mas pode enfrentar problemas de desempenho com grandes volumes de dados devido à sua complexidade. A complexidade do algoritmo é O(n * m * log m), onde n é o número de pedidos e m é o número de itens por pedido. A ordenação dos produtos por frequência (usando `arsort`) e a ordenação final dos produtos recomendados (usando `usort`) contribuem para essa complexidade.

### O que eu teria feito de diferente?

Hoje o algoritimo está sendo implementado dentro do serviço, isso faz com que se o algoritimo precisar ser alterado, vamos ter que refatorar a classe, poderia existir uma estrategia vinculado a uma factory que define qual algoritimo devemos utilizar

Ex:
```php

interface ProductRecommendationAlgoritimo {}

class DefaultProductRecommendationAlgoritimo implements ProductRecommendationAlgoritimo {}

class ProductRecommendationAlgoritimoFactory
{
    public function create($param) {
        return DefaultProductRecommendationAlgoritimo()
    }
}
```

### Navegando pelo projeto

- **src/Core**: Contém o código essencial para o funcionamento do projeto, incluindo funcionalidades, entidades e serviços.
  - **src/Core/Domain**: Abriga as entidades e os serviços responsáveis por garantir as regras de negócio da aplicação.
  - **src/Core/Application**: Contém os casos de uso da aplicação, como criar pedido e recomendar produto.
  - **src/Core/Infrastructure**: Inclui as implementações que interagem com serviços externos, como banco de dados e HTTP (repositórios, controllers).

- **src/Framework**: Contém classes auxiliares para configurar o framework ou classes utilitárias.

Esta organização ajuda a manter o código bem estruturado e facilita a manutenção e escalabilidade do projeto.


## Como executar a aplicação?

### Preparando o ambiente

Clone o repositório em uma pasta de sua preferência. Em seguida, abra o terminal e execute o seguinte comando para criar e iniciar os containers da aplicação (PHP, MySQL e Nginx):

```sh
git clone https://github.com/dominguesguilherme/product-recommendation.git
```

Garanta que você tenha o [Docker](https://docs.docker.com/desktop/install/windows-install/) instalado na sua máquina. Se estiver tudo certo, inicie a aplicação com o build da imagem:

```sh
docker-compose up --force-recreate --build -d
```

Durante a inicialização, o container PHP instalará as dependências do projeto e executará as migrations. Por isso, é necessário aguardar a conclusão desse processo antes de utilizar a aplicação.


```sh
docker-compose logs -f
```

A aplicação está pronta para uso quando o log a seguir for exibido:


```
product_recommendation | Executing script assets:install public [OK]
product_recommendation | - Done
product_recommendation | - Waiting for mysql in host 'mysql'
product_recommendation | - Done! MySQL is up - executing command
product_recommendation | - Running Migrations
product_recommendation | [notice] Migrating up to DoctrineMigrations\Version20240630235217
product_recommendation | [notice] finished in 662.3ms, used 14M memory, 1 migrations executed, 4 sql queries
product_recommendation | 
product_recommendation | - Done
product_recommendation | - Populating Products
product_recommendation | 
product_recommendation |  [OK] 10 rows affected.                                                         
product_recommendation | 
product_recommendation | - Done
product_recommendation | [01-Jul-2024 13:11:34] NOTICE: fpm is running, pid 1
product_recommendation | [01-Jul-2024 13:11:34] NOTICE: ready to handle connections
```

### Executandos os testes

Para executar os testes de unidade e de integração execute:

```sh
docker-compose exec php composer test
```

## Produtos Cadastrados

Como ainda não existe um cadastro de produtos, foram inseridos alguns registros para facilitar os testes. Abaixo estão os produtos cadastrados:

| ID                                     | SKU         | Nome     |
|----------------------------------------|-------------|----------|
| 1dcab66f-362c-49e4-a84c-6a2c1e1ea9a2   | CHUTEIRA01  | Chuteira |
| f245a8c8-0f5e-4e77-ae4d-79a99f52db41   | MEIA01      | Meia     |
| 80e27365-42f0-46cf-ae3d-cc24aeeb70b1   | BOLA01      | Bola     |
| be4d3de7-4a5f-4b8d-9d4a-55799d6a57c8   | CAMISA01    | Camisa   |
| 4cbf8c57-8d87-4aeb-9c0d-cde19b9e7b05   | CALCA01     | Calça    |
| ee8e53b5-8f79-419b-83a0-3f6f0dddcf35   | LUVA01      | Luva     |
| ea0d2a9a-bc3b-4fa4-b96e-9f1e1e512c1b   | BONÉ01      | Boné     |
| 1d2ab2f5-6bb2-41e2-a5ec-3f7819fdd6d1   | BOTA01      | Bota     |
| a4d0d2e5-06e8-4568-8c95-ec7d400e61a6   | SHORTS01    | Shorts   |
| 6c9c8e07-450b-4785-b63c-85a1636e4f32   | JAQUETA01   | Jaqueta  |

## Endpoints

### Criar um Pedido
Este endpoint cria um novo pedido. O ID do pedido e os IDs dos itens são gerados automaticamente pela aplicação e não precisam ser passados no payload.

#### Endpoint
`POST /orders`

#### Requisição
```sh
curl -X POST \
  http://localhost:8080/orders \
  -H 'Content-Type: application/json' \
  -d '{
    "items": [
        {
            "productId": "1dcab66f-362c-49e4-a84c-6a2c1e1ea9a2",
            "unitPrice": 10.0,
            "quantity": 1
        },
        {
            "productId": "f245a8c8-0f5e-4e77-ae4d-79a99f52db41",
            "unitPrice": 20.0,
            "quantity": 2
        }
    ]
}'
```

#### Resposta
Http Status Code 201 (Created)

### Buscar Recomendações
Esse endpoint tem o objetivo de obter as recomendações de produtos baseado no histórico de pedidos.

#### Endpoint
`GET  /products/{id}/recommendation`

#### Requisição
```sh
curl localhost:8080/products/1dcab66f-362c-49e4-a84c-6a2c1e1ea9a2/recommendation
```

#### Resposta
```json
{
    "products": [
        {
            "id": "f245a8c8-0f5e-4e77-ae4d-79a99f52db41",
            "sku": "MEIA01",
            "name": "Meia"
        },
        {
            "id": "80e27365-42f0-46cf-ae3d-cc24aeeb70b1",
            "sku": "BOLA01",
            "name": "Bola"
        }
    ]
}
```
