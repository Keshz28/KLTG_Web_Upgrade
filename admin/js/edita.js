/*
 * edita.js — Place To Stay (accommodation) admin editor.
 *
 * edit-accomodation.php has always referenced this file, but it did not exist,
 * so every pen icon on that page threw "editmodaltop is not defined" and no
 * edit modal ever opened. This restores the four editors:
 *
 *   editmodaltop  -> #edittopmodal   (accommodation_top,  img .../accommodation/top/)
 *   editmodalah   -> #editahmodal    (accommodation_h,    img .../accommodation/h/)
 *   editmodalabh  -> #editabhmodal   (accommodation_bh,   img .../accommodation/bh/)
 *   editmodalabks -> #editabksmodal  (accommodation_bks,  img .../accommodation/bks/)
 *
 * Each table renders per-row cells id="<field><key>-<rowid>" and the modal has
 * matching form controls id="<field><key>", so populating is a straight copy.
 */

/* Fill the "current image" thumbnail in an edit modal. Silently no-ops when the
   row has no image yet, so a missing file never breaks the modal. */
function setEditImagePreview(previewId, folder, filename) {
  var img = document.getElementById(previewId);
  if (!img) return;
  var name = (filename || "").trim();
  if (name) {
    img.src = folder + name;
    img.classList.remove("d-none");
  } else {
    img.removeAttribute("src");
    img.classList.add("d-none");
  }
}

/* Copy one row's cells into the matching modal fields, then show the modal.
   key      — the id suffix used by both the row cells and the form controls
   modalSel — jQuery selector of the modal to open
   folder   — image folder, relative to /admin/, for the preview thumbnail */
function fillAccommodationModal(id, key, modalSel, folder) {
  var cell = function (field) {
    var el = document.getElementById(field + key + "-" + id);
    return el ? el.innerText : "";
  };
  var field = function (name) {
    return document.getElementById(name);
  };

  var imagename = cell("filename");

  field("name" + key).value = cell("name");
  field("location" + key).value = cell("location");
  field("locationurl" + key).value = cell("locationurl");
  field("content" + key).value = cell("content");
  field("hours" + key).value = cell("hours");
  field("phone" + key).value = cell("phone");
  field("order" + key).value = cell("order");
  field(key + "id").value = id;
  field("imagename" + key).value = imagename;

  var coordsCell = document.getElementById("mapcoords" + key + "-" + id);
  var coordsField = field("mapcoords" + key);
  if (coordsField) coordsField.value = coordsCell ? coordsCell.innerText : "";

  setEditImagePreview("preview" + key, folder, imagename);

  // A file input keeps its previous selection when the modal is reopened; clear
  // it so reopening a row never silently re-uploads the last picked file.
  var fileInput = field("fileToUpload" + key + "edit");
  if (fileInput) fileInput.value = "";

  $(modalSel).modal("show");
}

function editmodaltop(id) {
  fillAccommodationModal(id, "atop", "#edittopmodal", "../assets/img/accommodation/top/");
}

function editmodalah(id) {
  fillAccommodationModal(id, "ah", "#editahmodal", "../assets/img/accommodation/h/");
}

function editmodalabh(id) {
  fillAccommodationModal(id, "abh", "#editabhmodal", "../assets/img/accommodation/bh/");
}

function editmodalabks(id) {
  fillAccommodationModal(id, "abks", "#editabksmodal", "../assets/img/accommodation/bks/");
}

/* Bootstrap 4 exposes toasts only through the jQuery plugin — there is no global
   `bootstrap` object (that is Bootstrap 5). errors2.php calls this on every
   save, so it has to work under BS4 or the admin gets no feedback at all. */
function toastupdate(body) {
  var toastbody = document.getElementById("toast-body");
  if (toastbody) toastbody.innerHTML = body;
  $("#liveToast").toast("show");
}

$(document).ready(function () {
  // dragreorder.js destroys these again on tables that have order links; the
  // init still matters for search/paging before a drag happens.
  ["#dataTable", "#dataTable1", "#dataTable2", "#dataTable3", "#dataTable4"].forEach(function (sel) {
    var $t = $(sel);
    if ($t.length && !$.fn.dataTable.isDataTable(sel)) {
      $t.DataTable({ ordering: false, lengthMenu: [5, 10, 25, 50, 75, 100] });
    }
  });
});
