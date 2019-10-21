# Teste Crawler + API

Esse sistema busca dados sobre veiculos a venda via Crawler lendo o HTML
e os dados são disponibilizados via api

## Instalação

Clone o projeto e execute o composer
Configure o .env de acordo com seu banco de dados e crie o banco "cars"
Execute o comando php artisan migrate

## Crawler

Para obter os veiculos basta executar os commands em sequencia:
php artisan get:brands
php artisan get:carsByBrand
php artisan get:carDetails

## API

A Api possui apenas 2 endpoints para pesquisa, um que lista todos os veiculos e permite filtros, e outro que busca por ID.
São retornados nas consultas os dados do veiculo, marca, opciconais e fotos.

Index com filtros (POST)
    http://localhost:8000/api/cars/
filtros:
    brand(marca), model(modelo do veiculo), year_min(ano minimo), year_max(ano maximo), city(cidade);

Busca por ID (GET)
    http://localhost:8000/api/cars/{ID}

## Observações

Pode demorar um pouco para buscar os dados, esta disponivel na raiz do projeto um arquivo cars.sql para subir o banco caso necessário