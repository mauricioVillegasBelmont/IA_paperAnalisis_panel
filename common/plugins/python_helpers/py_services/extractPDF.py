#!/usr/bin/env python3
# script.py 
import sys
from pdfminer.high_level import extract_text

file_static_dir = sys.argv[1]




def main():
  text = extract_text(file_static_dir)
  print(text)


if __name__ == "__main__":
  main()