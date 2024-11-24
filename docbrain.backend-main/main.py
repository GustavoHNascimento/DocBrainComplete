import os
from pdf2image import pdfinfo_from_path, convert_from_path
from PIL import Image
from pytesseract import pytesseract
import openai
import os

openai.api_key = "chave api"

def dividir_texto(texto, tamanho_chunk=1000):
    return [texto[i:i + tamanho_chunk] for i in range(0, len(texto), tamanho_chunk)]

def criar_template_resumo(titulo_documento, autor, data_documento, resumo_conteudo, conclusao):
    template_resumo = f"""
    **Título do Documento:** {titulo_documento}
    **Autor:** {autor}
    **Data do Documento:** {data_documento}
    **Resumo do Conteúdo:** {resumo_conteudo}
    **Conclusão/Observações:** {conclusao}
    """
    return template_resumo

def enviar_chunk_para_chatgpt(chunk, modelo="gpt-4"):
    try:
        resposta = openai.ChatCompletion.create(
            model=modelo,
            messages=[
                {"role": "system", "content": "Você é um assistente que ajuda a resumir e responder perguntas sobre texto."},
                {"role": "user", "content": chunk}
            ]
        )
        return resposta['choices'][0]['message']['content']
    except Exception as e:
        return f"Erro ao processar chunk: {e}"

def processar_pdf_em_chunks(pdf_path, tamanho_chunk=1000):

    info = pdfinfo_from_path(pdf_path, userpw=None, poppler_path=None)
    paginas = info['Pages']

    texto_completo = ""
    for i in range(paginas):

        page = convert_from_path(pdf_path, first_page=i+1, last_page=i+1)
        page[0].save(f'out{i}.jpg', 'JPEG')


        image = Image.open(f'out{i}.jpg')
        text = pytesseract.image_to_string(image)
        texto_completo += text + "\n"
        
        os.remove(f'out{i}.jpg')  

    chunks = dividir_texto(texto_completo, tamanho_chunk)
    
    # mandar chunks para o ChatGPT
    respostas = []
    for idx, chunk in enumerate(chunks):
        print(f"\nEnviando Chunk {idx + 1} para o ChatGPT...")
        resposta = enviar_chunk_para_chatgpt(chunk)
        respostas.append(resposta)
        print(f"Resposta do ChatGPT para Chunk {idx + 1}:\n{resposta}")
    

    titulo_documento = "Título Exemplo"
    autor = "Autor Desconhecido"
    data_documento = "Data Indefinida"
    resumo_conteudo = "\n".join(respostas[:3]) 
    conclusao = "Texto enviado ao ChatGPT para perguntas específicas."

    resumo = criar_template_resumo(titulo_documento, autor, data_documento, resumo_conteudo, conclusao)
    print("\n--- Resumo Gerado ---\n")
    print(resumo)

    return respostas

def perguntar_sobre_texto(respostas, pergunta, modelo="gpt-4"):
    contexto = "\n".join(respostas)
    try:
        resposta = openai.ChatCompletion.create(
            model=modelo,
            messages=[
                {"role": "system", "content": "Você é um assistente que responde perguntas baseadas no texto fornecido."},
                {"role": "user", "content": f"Baseado no texto a seguir, responda: {pergunta}\n\nTexto:\n{contexto}"}
            ]
        )
        return resposta['choices'][0]['message']['content']
    except Exception as e:
        return f"Erro ao responder pergunta: {e}"



pdf_path = "C:/Users/User/Desktop/DockBrain/docbrain.backend-main/teste-gabriel-pdf.pdf"


respostas_chunks = processar_pdf_em_chunks(pdf_path, tamanho_chunk=500)

pergunta = "Qual é o tema principal do documento?"
resposta_pergunta = perguntar_sobre_texto(respostas_chunks, pergunta)
print("\n--- Resposta para a pergunta ---\n")
print(resposta_pergunta)


