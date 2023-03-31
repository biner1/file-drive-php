window.onload = fetchFiles();
// select all checkbox
const selectAllCheckbox = document.getElementById('select-all');
selectAllCheckbox.addEventListener('change', () => {
    const checkboxes = document.querySelectorAll('input[name="row[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
});

const homeBtn = document.getElementById('home-btn');
homeBtn.addEventListener('click', (event) => {
  event.preventDefault();
  fetchFiles();
});

// get directory
let list = document.querySelector("#file-list");
list.addEventListener('click', event => {
    if (event.target.tagName === 'A') {
        if(event.target.classList.contains('directory-link')){
            event.preventDefault();
            let dirPath = event.target.getAttribute("href");
            fetchFiles(dirPath);
        }
        }
    });

// delete file
const deleteButton = document.getElementById('deleteFile');
deleteButton.addEventListener('click', async () => {
  const checkboxes = getCheckedCheckboxes();
  const selectedFiles = Array.from(checkboxes).map(checkbox => checkbox.closest('tr').querySelector('a').getAttribute('href'));

  if (selectedFiles.length === 0) {
    showAlert('Please select at least one file to delete.', 'danger');
    return;
  }

  try {
    const response = await fetch('controller/file-manager.php', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ files: selectedFiles })
    });

    if (!response.ok) {
      throw new Error('An error occurred while deleting the selected files.');
    }

    console.log(await response.text());
    fetchFiles(filesToGet());
  } catch (error) {
    alert(error.message);
  }
});

// upload file
const form = document.getElementById('upload-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    try {
        const fileInput = document.getElementById('file-input');
        if (fileInput.files.length === 0) {
          showAlert('No file selected.', 'danger')
            return;
        }
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('path', document.getElementById('directory').innerHTML);

        const response = await fetch('controller/file-manager.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            console.log(await response.text());
            fileInput.value = '';
            fetchFiles(filesToGet());
        } else {
            throw new Error('An error occurred while uploading the file.');
        }
    } catch (error) {
        showAlert(error.message, 'danger');

    }
});

// create folder
const newfolder = document.getElementById('createFolder');
newfolder.addEventListener('click', () => {
  const folderName = document.getElementById('foldername').value;
  const path = document.getElementById('directory').innerHTML;

  if (folderName.length < 1) {
    showAlert('Please enter the folder name.', 'danger');
    return;
  }

  const formData = new FormData();
  formData.append('folder', folderName);
  formData.append('path', path);

  fetch('controller/file-manager.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (response.ok) {
      console.log(response.text());
      fetchFiles(filesToGet());
    } else {
      throw new Error('An error occurred while creating the folder.');
    }
  })
  .catch(error => alert(error.message));
});

// rename file
const renameBtn = document.getElementById('rename');
renameBtn.addEventListener('click', () => {
  const selectedFiles = getSelectedFiles();
  if (selectedFiles.length === 1) {
    const fileToRename = selectedFiles[0];
    const fileParts = getFileParts(fileToRename);
    setRenameInputValue(fileParts);
    toggleModal('fileRenameModal',true);
  }
  else{
    showAlert('Please select one file to rename.', 'danger');
  }
});

const modalRenameBtn = document.getElementById('renameFile');
modalRenameBtn.addEventListener('click', async () => {
    const text = document.getElementById('renametext').value;
    const extension = document.getElementById('extension').innerHTML;
    
    if (text.length < 1 || text.length > 250) {
        showAlert('Please enter a valid file name.', 'danger');
        return;
    }
    const checkboxes = getCheckedCheckboxes();
    const selectedFile = checkboxes[0].closest('tr').querySelector('a').getAttribute('href');
    const renameTo = text + (extension === 'dir' ? '' : extension);
    const data = { file: selectedFile, rename: renameTo };

    try {
        const response = await fetch('controller/file-manager.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            console.log(await response.text());
            fetchFiles(filesToGet());
        } else {
            throw new Error('An error occurred while renaming the selected files.');
        }
    } catch (error) {
        showAlert(error.message, 'danger');
    }
});

// functions
async function fetchFiles(dirPath = 'uploads') {
    try {
      const response = await fetch(`controller/file-manager.php?dir=${dirPath}`);
  
      if (response.ok) {
        const filesHtml = await response.json();
        document.getElementById('file-list').innerHTML = buildRowsFromJson(filesHtml);
        // document.getElementById('file-list').innerHTML = filesHtml;

        const path = dirPath.replace(/^uploads/, '');
        document.getElementById('directory').innerHTML = path;
      } else {
        throw new Error('An error occurred while fetching the files.');
      }
    } catch (error) {
      alert(error.message);
    }
}

function getCheckedCheckboxes() {
    return Array.from(document.querySelectorAll('input[name="row[]"]:checked'));
}

function getSelectedFiles() {
  const checkboxes = document.querySelectorAll('input[name="row[]"]:checked');
  const selectedFiles = Array.from(checkboxes).map(checkbox => checkbox.value);
  return selectedFiles;
}

function getFileParts(filename) {
    const parts = filename.split('.');
    return {
        name: parts[0],
        extension: parts.length > 1 ? `.${parts[1]}` : ''
    };
}

function setRenameInputValue(fileParts) {
    const renameInput = document.getElementById('renametext');
    const extensionElement = document.getElementById('extension');
    renameInput.value = fileParts.name;
    extensionElement.innerHTML = fileParts.extension || 'dir';
}


function buildRowsFromJson(data) {
    let rows = '';
    data.forEach(file => {
      let type = '';
      let linkTarget = 'target="_blank"';
      if (file.type === 'directory') {
        type = ' directory-link';
        linkTarget = '';
      }
      rows += `<tr>
      <td><input type="checkbox" name="row[]" value="${file.name}"><a class="p-3${type}" href="${file.dirpath}" ${linkTarget}>${file.name}</a></td>
      <td>${formatDate(file.modified)}</td>
      <td>${file.size}</td></tr>`;
    });
    return rows;
  }

function formatDate(timestamp) {
  const date = new Date(timestamp * 1000);
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  const monthIndex = date.getMonth();
  const month = months[monthIndex];
  const day = date.getDate();
  const year = date.getFullYear();
  const hours = date.getHours();
  const minutes = date.getMinutes().toString().padStart(2, '0');
  const ampm = hours >= 12 ? 'PM' : 'AM';
  const formattedTime = `${month} ${day}, ${year} ${hours % 12}:${minutes}${ampm}`;
  return formattedTime;
}

function showAlert(message, type) {
  const alertDiv = document.createElement('div');
  alertDiv.classList.add('alert', `alert-${type}`, 'position-absolute', 'start-50', 'translate-middle-x', 'top-0', 'mt-3', 'text-center');
  alertDiv.style.zIndex = '9999';
  alertDiv.setAttribute('role', 'alert');
  alertDiv.innerText = message;
  document.body.appendChild(alertDiv);
  
  setTimeout(() => {
    alertDiv.remove();
  }, 5000);
}

function toggleModal(modalId, show = false) {
  const modal = new bootstrap.Modal(document.getElementById(modalId));
  if (show===true) {
    modal.show();
  } else {
    modal.hide();
  }
}

function filesToGet() {
  path = document.getElementById('directory').innerHTML;
  return 'uploads' + path;
}