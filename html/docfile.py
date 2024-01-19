
from docx import Document
import fitz  # PyMuPDF
import os
import sys

def read_document(file_path):
    if file_path.lower().endswith(".pdf"):
        return read_pdf(file_path)
    elif file_path.lower().endswith(".docx"):
        return read_word(file_path)
    else:
        print(f"Unsupported file format for {file_path}")
        return ""

def read_pdf(file_path):
    doc = fitz.open(file_path)
    text = ""
    for page_number in range(doc.page_count):
        page = doc[page_number]
        text += page.get_text("text")
    return text

def read_word(file_path):
    doc = Document(file_path)
    text = ""
    for paragraph in doc.paragraphs:
        text += paragraph.text + "\n"
    return text

def process_files(file_paths):
    for file_path in file_paths:
        extracted_text = read_document(file_path)
        file_name = os.path.splitext(os.path.basename(file_path))[0]

        with open(f"uploads/{file_name}.txt", "w", encoding="utf-8") as file:
            file.write(extracted_text)
        print(f"Text extracted from {file_path} and saved to uploads/{file_name}.txt")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python doc.py <file_path1> <file_path2> ...")
        sys.exit(1)

    file_paths = sys.argv[1:]
    process_files(file_paths)