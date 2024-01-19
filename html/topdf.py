from docx2pdf import convert
import sys

def convert_docx_to_pdf(docx_file, pdf_file):
    convert(docx_file, pdf_file)
    print(f"Converted {docx_file} to {pdf_file}")

if __name__ == "__main__":
    # Lấy đường dẫn tệp từ tham số
    docx_file = sys.argv[1]  

    # Tạo tên file pdf
    pdf_file = docx_file.replace(".docx", ".pdf")

    # Chuyển đổi
    convert_docx_to_pdf(docx_file, pdf_file)
