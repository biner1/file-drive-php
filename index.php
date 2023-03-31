<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="asset/css/bootstrap.min.css" rel="stylesheet">
    <script src="asset/js/bootstrap.bundle.min.js" defer></script>
    <script src="asset/js/script.js" defer></script>

    <title>MyDrive</title>
</head>

<body>
<header>
    
    <nav class="navbar bg-secondary navbar-dark">
        <div class="container-fluid ">
            <a class="navbar-brand">MyDrive</a>

            <ul class="nav justify-content-end column-gap-2">
                <li class="nav-item">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#fileDeleteModal">Delete</button>
                </li> 
                <li class="nav-item">
                    <button type="button" id="rename" class="btn btn-light" data-bs-target="#fileRenameModal">Rename</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#folderCreateModal">New Folder</button>
                </li>
            </ul>

        </div>
    </nav>

    <div class="d-flex align-items-center p-2 bg-light">
        <div class='mx-2'><a href='#' id='home-btn'>Home </a></div>
          <div id="directory"></div>
    </div>
    
</header>

<main>
    

    <div class="m-3">
        <form id="upload-form" enctype="multipart/form-data">
            <div class="mb-3 d-flex">
                <input id="file-input" type="file" name="file" class="form-control">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>



        <table class="table" id="table">
            <thead>
                <tr>
                    <th scope="col" class="col-5"><input type="checkbox" id="select-all"><span class="p-3">Name</span></th>
                    <th scope="col">Last Modified</th>
                    <th scope="col" class="col-2">Size</th>

                </tr>
            </thead>

            <tbody class="table-group-divider" id="file-list">
            </tbody>
        </table>
    </div>




<!-- File Delete Modal -->
<div class="modal fade" id="fileDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Deleting Files</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure about deleting the selected files?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" id="deleteFile" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
      </div>
    </div>
  </div>
</div>


<!-- File Rename Modal -->
<div class="modal fade" id="fileRenameModal" tabindex="-1" aria-labelledby="RenameModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="RenameModalLabel">Rename File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex">
        <input id="renametext" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        <span class="border bg-light"><div id="extension" class="p-2"></div></span>
      </div>
      <div class="modal-footer">
        <button type="button" id="renameFile" data-bs-dismiss="modal" class="btn btn-primary">Rename</button>
      </div>
    </div>
  </div>
</div>


<!-- Folder Create Modal -->
<div class="modal fade" id="folderCreateModal" tabindex="-1" aria-labelledby="folderCreateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="folderCreateModalLabel">New Folder</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="foldername" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="New Folder">
      </div>
      <div class="modal-footer">
        <button type="button" id="createFolder" data-bs-dismiss="modal" class="btn btn-primary">Create</button>
      </div>
    </div>
  </div>
</div>

</main>
    
</body>
</html>