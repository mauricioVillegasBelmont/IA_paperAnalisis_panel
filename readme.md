## configure .imi.dist as .ini
- common/plugins/python_helpers
- common/plugins/iaservices

## read write files permision
```
 sudo chmod 775 -R  site_media/public/
 sudo chown $USER:www-data -R  site_media/public/
```

## CLI run python sample
```
 python3 ${DOCUMENT_ROOT}/common/plugins/python_helpers/py_services/extractPDF.py ${DOCUMENT_ROOT}/site_media/public/papers/sample.pdf
```

