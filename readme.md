# Teste Crawler + API

Esse sistema busca dados sobre veiculos a venda via Crawler lendo o HTML
e os dados são disponibilizados via api

## Instalação

Clone o projeto e execute o composer<br/>
Configure o .env de acordo com seu banco de dados e crie o banco "cars"<br/>
Execute o comando php artisan migrate<br/>

## Crawler

Para obter os veiculos basta executar os commands em sequencia:<br/>
php artisan get:brands<br/>
php artisan get:carsByBrand<br/>
php artisan get:carDetails<br/>

## API

A Api possui apenas 2 endpoints para pesquisa, um que lista todos os veiculos e permite filtros, e outro que busca por ID.
São retornados nas consultas os dados do veiculo, marca, opciconais e fotos.

Index com filtros (POST)<br/>
    http://localhost:8000/api/cars/<br/>
filtros:<br/>
    brand(marca), model(modelo do veiculo), year_min(ano minimo), year_max(ano maximo), city(cidade);<br/>

Busca por ID (GET)<br/>
    http://localhost:8000/api/cars/{ID}<br/>

## Observações

Pode demorar um pouco para buscar os dados, esta disponivel na raiz do projeto um arquivo cars.sql para subir o banco caso necessário