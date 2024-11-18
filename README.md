# PDF Text Extractor

Este repositório contém um script em Python que converte um arquivo PDF em imagens, extrai o texto de cada página usando a biblioteca Tesseract OCR, e armazena o texto extraído em um arquivo de texto. Comentários adicionais no código incluem exemplos de integração com o MongoDB para salvar dados.

## Pré-requisitos

Antes de executar o script, certifique-se de ter os seguintes itens instalados:

- Python 3.x
- Bibliotecas necessárias, que podem ser instaladas com o seguinte comando:
  ```bash
  pip install pdf2image pillow pytesseract

- Poppler: necessário para a conversão de PDFs em imagens. Em sistemas baseados em Debian/Ubuntu, pode ser instalado com:
  ```bash
  sudo apt-get install poppler-utils
  ```

- Tesseract OCR: necessário para a extração de texto das imagens. Em sistemas baseados em Debian/Ubuntu, pode ser instalado com:
  ```bash
  sudo apt-get install tesseract-ocr
    ```

## Como usar
Coloque o arquivo PDF que deseja analisar no mesmo diretório que o script, e nomeie-o como teste-gabriel-pdf.pdf (ou atualize o nome no código para corresponder ao arquivo desejado).

Execute o script:
  ```bash
  python3 main.py
  ```

## O script irá:

- Analisar o PDF, página por página.
- Converter cada página em uma imagem.
- Usar o Tesseract OCR para extrair o texto de cada imagem.
- Salvar o texto extraído no arquivo processo.txt.


## Estrutura do Projeto
- script.py: o script principal para extração de texto.
- processo.txt: arquivo gerado contendo o texto extraído do PDF.
- teste-gabriel-pdf.pdf: exemplo de PDF para ser processado pelo script (não incluído).

## MongoDB (Comentado)
O código inclui uma integração comentada com o MongoDB, que pode ser utilizada para armazenar dados extraídos ou processados. Para habilitar a funcionalidade:

Descomente as linhas relacionadas ao MongoDB.
Instale a biblioteca pymongo:
  ```bash
  pip install pymongo
  ```

# Atualize as credenciais e URL de conexão do MongoDB conforme necessário.

## Contribuições
Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests.

## Licença
Este projeto está licenciado sob os termos da MIT License.
