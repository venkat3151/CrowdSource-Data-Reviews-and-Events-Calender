from PyPDF2 import PdfFileWriter, PdfFileReader
import glob
import csv
import os
import sys
import math

import logging
logging.basicConfig(filename='app.log', filemode='w', format='%(name)s - %(levelname)s - %(message)s')

try:
    source_filepath=sys.argv[1]
    sfp=source_filepath.split('/',2)

    result_filename_prefix=sfp[2]
    result_filename_prefix=result_filename_prefix.split('.')[0]
    dest_path='../uploads/'+result_filename_prefix
    split=int(sys.argv[2])
    logging.warning(sys.argv[2])

    file_type=sys.argv[3]


    if not os.path.exists(dest_path): # if the directory does not exist
        os.makedirs(dest_path) # make the directory
    else: # the directory exists
        #removes all files in a folder
        for the_file in os.listdir(dest_path):
            file_path = os.path.join(dest_path, the_file)
            try:
                if os.path.isfile(file_path):
                    os.unlink(file_path) # unlink (delete) the file
            except:
                print("Exception ocurred")


    if(file_type=='application/pdf' or file_type=='application/x-pdf' ):
            pdfs = glob.glob(source_filepath)
            print(pdfs)
            for pdf in pdfs:         
                inputpdf = PdfFileReader(pdf, "rb")
                logging.warning(str(inputpdf.numPages//split))
                for i in range((inputpdf.numPages//split)+1):
                    j=0
                    i=i*split
                   
                    output = PdfFileWriter()
                    while j<split:
                        if(i+j == inputpdf.numPages):
                            target_filename = result_filename_prefix+'_'+str(i)+'.pdf'
                            target_filepath = os.path.join(dest_path, target_filename)
                            with open(target_filepath, 'wb') as out:
                                output.write(out)
                                break
                        output.addPage(inputpdf.getPage(j+i))
                        j=j+1
                        if((j)==split):
                            target_filename = result_filename_prefix+'_'+str(i)+'.pdf'
                            target_filepath = os.path.join(dest_path, target_filename)       
                            with open(target_filepath, 'wb') as out:
                                output.write(out)

                      
    else:
            if split <= 0:
                    raise Exception('split must be > 0')

            with open(source_filepath, 'r') as source:
                    reader = csv.reader(source)
                    headers = next(reader)

                    file_number = 0
                    records_exist = True

                    while records_exist:

                        i = 0
                        target_filename = result_filename_prefix+'_'+str(file_number)+'.csv'
                        target_filepath = os.path.join(dest_path, target_filename)

                        with open(target_filepath, 'w') as target:
                            writer = csv.writer(target)

                            while i < split:
                                if i == 0:
                                    writer.writerow(headers)

                                try:
                                    writer.writerow(next(reader))
                                    i += 1
                                except:
                                    records_exist = False
                                    break

                        if i == 0:
                            os.remove(target_filepath)

                        file_number += 1
except Exception as e:
    logging.warning("Exception occurred", exc_info=True)




