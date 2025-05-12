## configure .imi.dist as .ini
- common/plugins/python_helpers
- common/plugins/iaservices

## read write files permision
```
 sudo chmod 775 -R  site_media/public/
 sudo chown $USER:www-data -R  site_media/public/
```

## sample CLI run python 
```
pip install pdfminer.six
```
```
 python3 ${DOCUMENT_ROOT}/common/plugins/python_helpers/py_services/extractPDF.py ${DOCUMENT_ROOT}/site_media/public/papers/sample.pdf
```

> ### herramientas de utilidad para paper_queue
> -  bulk - rename files
> - remove filename blankspaces
> - remove filename special o conflictive chars
> 
> el uso de paper_queue de omento es solo manual
> 
> link:
> https://www.bulkrenameutility.co.uk/


## googleSheets
- Configutre auth service at https://console.cloud.google.com/auth/scopes
  - /auth/drive.appdata
  - /auth/spreadsheets	
  - /auth/drive
- configure your authentication docs on https://cloud.google.com/docs/authentication/set-up-adc-local-dev-environment
  - cli: `gcloud auth application-default login  --scopes=https://www.googleapis.com/auth/cloud-platform,https://www.googleapis.com/auth/spreadsheets`
  - cli: `cp /home/${USER}/.config/gcloud/application_default_credentials.json ${APP_DIR}/common/plugins/google_sheets/credentials.json`
  - cli: `sudo hmod 760 -R ${APP_DIR}/common/plugins/google_sheets/`
  - cli: `sudo chown $USER:www-data -R ${APP_DIR}/common/plugins/google_sheets/`
- config your ${APP_DIR}/common/plugins/google_sheets/config.ini