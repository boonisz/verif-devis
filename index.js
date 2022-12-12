// Init ======================================================================
// Global variables
currentTab = null;  // to keep trace of the current tab
devisCounter = 0;  // How many devis tab created
helperAnnexes = {}; // A dictionnary to list all annexes (text > id)
helperAnnexesCounter = 0;  // A counter for annexes
switchCounter = 0; // How many switch created
accordionCounter = 0; // How many accordion created
accordionItemCounter = 0; // How many item accordion created

// Load data
var dataPosts = jsyaml.load(dataPostsYAML);
console.log(dataPosts);
var dataCommun = jsyaml.load(dataCommunYAML);
console.log(dataCommun);

// Populate
populatePostSelector();  // Fill post selector
populateDate("current-date");


/*/ To init tabs for tests ###########################################
window.onload = function() {
    var postSelector = document.getElementById("postSelector");
    postSelector.selectedIndex = 1;  // Reset selector
    createNewDevis();
    postSelector.selectedIndex = 2;  // Reset selector
    createNewDevis();
};
// ##################################################################*/
// ===========================================================================





// Populate functions ========================================================
function populateDate(idName) {
    date = new Date();
    year = date.getFullYear();
    month = (date.getMonth() + 1).toString().padStart(2, '0');
    day = date.getDate().toString().padStart(2, '0');
    document.getElementById(idName).placeholder = year + "-" + month + "-" + day;
}


function populatePostSelector() {
    var post_selector = document.getElementById("postSelector");
    for (const postName in dataPosts) {
        console.log("Add devis option: " + postName);
        var post = document.createElement("option");
        post.textContent = postName;
        post.value = postName;
        post_selector.appendChild(post);
    }

    // Add general tab
    const triggerEl = document.querySelector('#general-tab');
    var tabTrigger = new bootstrap.Tab(triggerEl);
}

// ===========================================================================
// URL management
// ===========================================================================

// Add anchor to URL and automatically select tabs (#doc, #app, #contact...)
$(document).ready(() => {
    // If anchor exists, select it, otherwise use #doc anchor
    let anchor = location.href.split("#")[1] ? location.href.split("#")[1] : "doc"

    // Update URL with anchor
    updateURLWithAnchor("#" + anchor);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 400);

    // When select another page, update URL
    $('.doc a, a.menu').on("click", function() {
        const anchor = $(this).attr("href");
        updateURLWithAnchor(anchor);
    });
});

function updateURLWithAnchor(anchor) {
    if(anchor.startsWith("http")) {  // if external link do not touch URL
        return;
    }

    // Select page
    $('#myTab a[href="'+anchor+'"]').tab("show");

    // URL is something like : ROOT?PARAMETERS#ANCHOR   (toto.com/index.php?profil=murmur#doc)
    let urlRootParameters = location.href.split("#")[0];  // Remove anchor

    let parameters = urlRootParameters.split("?")[1];  // Isolate parameters
    let root = urlRootParameters.split("?")[0];  // Remove parameters

    parameters = parameters ? "?" + parameters : "";

    // Update URL
    url = root + parameters + anchor;
    history.replaceState(null, null, url);
}


// ===========================================================================
// Tab management
// ===========================================================================
// Create new post tab
function createNewDevis() {
    // Read selector to find devis post name to create
    var postSelector = document.getElementById("postSelector");
    var post_i = postSelector.selectedIndex;
    var postName = document.getElementsByTagName("option")[post_i].value;
    postSelector.selectedIndex = 0;  // Reset selector

    // Add new devis (tabs and content)
    populateNewDevis(postName)

    // Change tab to select new one
    const triggerEl = document.querySelector('#myDevis li:last-child a');
    var tabTrigger = new bootstrap.Tab(triggerEl);
    tabTrigger.show();

    currentTab = document.querySelector('#myDevis li a.active');

    // When click on x, remove tab
    $("#myDevis a.active .tab-close").on("click", function (e) {
        // If close current tab, next current tab will be first tab
        if (currentTab.id == $(this).parent()[0].id) {
            console.log("close current tab");
            currentTab = document.querySelector("#general-tab");
        }

        // Remove tab and content
        var tab = $(this).parent().parent();
        var tabContentId = $(this).parent().attr("href");
        var tabContent = document.querySelector('#' + tabContentId);
        tabContent.remove();
        tab.remove();

        // Show new current tab (click on x change new tab
        bootstrap.Tab.getInstance(currentTab).show();

        updateAnnexes();
        updateSommaire();
    });
    selectTab();
    updateSommaire();
}


function selectTab() {
    currentTab = document.querySelector('#myDevis li a.active');

    // Enable helper popover (? icon)
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

    // Popovers alive while being hovered
    $(".active .pop").popover({ trigger: "manual" , animation:false})
        .on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                }
            }, 300);
    });
}


function updateSommaire() {
    console.log("updateSommaire");
    posts = document.querySelectorAll('#myDevis li:not(:first-child)');  // First tab is the general info tab
    sommaire = document.querySelector("#sommaire");
    sommaire.innerHTML = "";
    for (let i = 0; i < posts.length; i++) {
        let postLi = document.createElement("li");
        postLi.innerHTML = posts[i].textContent;

        sommaire.appendChild(postLi);
    }

    // Add annexes
    let postLi = document.createElement("li");
    postLi.innerHTML = "Annexes";
    sommaire.appendChild(postLi);
};


// ===========================================================================
// Populate devis tab
// ===========================================================================
function populateNewDevis(postName) {
    console.log("Create new devis for " + postName);

    let devisId = "devis" + devisCounter;  // Permits to have unique id for each field, even when two tabs with same post
    devisCounter++;

    // Add new tab
    let devisList = document.getElementById('myDevis');
    let newDevisLi = document.createElement("li");
    newDevisLi.className = "nav-item";
    newDevisLi.role="presentation";

    // Button with x to remove tab ------------------------
    let closeButton = document.createElement("a");
    closeButton.className = "nav-link";
    closeButton.id = devisId + "-tab";
    closeButton.type = "button";
    closeButton.setAttribute("role", "tab");
    closeButton.setAttribute("onClick", "selectTab();");
    closeButton.setAttribute("data-bs-toggle", "tab");
    closeButton.setAttribute("data-bs-target", "#" + devisId);
    closeButton.setAttribute("href", devisId);
    closeButton.setAttribute("aria-controls", devisId);
    closeButton.setAttribute("aria-selected", true);
    closeButton.innerHTML = postName + " <i class='bi-x tab-close'></i>";
    newDevisLi.appendChild(closeButton);
    devisList.appendChild(newDevisLi);


    // Add new tab content --------------------------------
    let devisContentList = document.getElementById("myDevisContent");
    let devisContent = document.createElement("div");
    devisContent.className = "tab-pane show ms-1 ms-sm-4 mb-4";
    devisContent.id = devisId;
    devisContent.setAttribute("role", "tabpanel");
    devisContent.setAttribute("aria-labelledby", devisId + "-tab");
    devisContentList.appendChild(devisContent);

    // Hidden title visible when printing
    let devisTitleForPrint = document.createElement("h2");
    devisTitleForPrint.className = "d-none d-print-block post-title";
    devisTitleForPrint.innerHTML = postName;
    devisContent.appendChild(devisTitleForPrint);

    // Add text fields for devis information: number, company and title
    addDevisInfo(devisContent, devisId);

    // Add common checks --------------------------------------------------------------------------
    addTitle("h3", "Mentions communes à tous les postes de travaux", devisContent);
    let accordion = document.createElement("div");
    accordion.className = "accordion mb-4";
    accordion.id = "accordion_" + accordionCounter;
    accordionCounter += 1;
    devisContent.appendChild(accordion);
    for(const key in dataCommun) {
        populateAccordion(accordion, dataCommun[key], key, accordion.id, devisId);
    }

    // Add post checks ----------------------------------------------------------------------------
    addTitle("h3", "Mentions spécifiques à ce poste de travaux", devisContent);
    let accordionPost = document.createElement("div");
    accordionPost.className = "accordion my-4";
    accordionPost.id = "accordion_" + accordionCounter;
    accordionCounter += 1;
    devisContent.appendChild(accordionPost);
    for(const key in dataPosts[postName]) {
        populateAccordion(accordionPost, dataPosts[postName][key], key, accordionPost.id, devisId);
    }

    populateCommentsSection(devisContent, dataPosts[postName], devisId);
    addPrintButton(devisContent);
}

function addTitle(tag, text, elem)
{
    let title = document.createElement(tag);
    title.innerHTML = text;
    elem.appendChild(title);
}


/**
 * Add some input fields to write some information about the devis (title, devis number, company name)
 * @param  devisContent Tab content for this devis, where input fields will be added
 * @param  devisId      Devis id to have unique id for each field, even when two tabs with same post
 */
function addDevisInfo(devisContent, devisId) {
    let devisInfoForm = document.createElement("form");
    devisInfoForm.className = "devisInfoForm my-4";
    devisContent.appendChild(devisInfoForm);

    addDevisInfoRow(devisInfoForm, "Titre", devisId + "_" + "title");
    addDevisInfoRow(devisInfoForm, "Numéro de devis", devisId + "_" + "number");
    addDevisInfoRow(devisInfoForm, "Nom de l'entreprise", devisId + "_" + "entreprise");
}


/**
 * Add an input field to write some information about the devis (title, devis number, company name)
 * @param  devisInfoForm Form section where input field will be added
 * @param  label         Label of input
 * @param  id            Id used by label-input binôme
 */
function addDevisInfoRow(devisInfoForm, label, id) {
    let row = document.createElement("div");
    row.className = "row p-1 align-items-center noPrintDisplay";
    row.id = id + "_div"
    devisInfoForm.appendChild(row);

    // Label
    let rowLabel = document.createElement("label");
    rowLabel.className = "col-auto col-form-label";
    rowLabel.innerHTML = label;
    rowLabel.setAttribute("for", id);
    row.appendChild(rowLabel);
    // Input devis number
    let rowInputDiv = document.createElement("div");
    rowInputDiv.className = "col-auto";
    row.appendChild(rowInputDiv);
    let rowInput = document.createElement("input");
    rowInput.className = "col form-control commentaire";
    rowInput.id = id;
    rowInput.setAttribute("autocomplete", "off");
    rowInput.setAttribute('onblur', "noPrintDisplayIfEmpty(" + id + ", " + id + "_div);");
    rowInputDiv.appendChild(rowInput);
}


function toggleSection(devisId, switchId) {
    console.log("Toggle: " + switchId);
    let switchFields = $("." + switchId + ".field, ." + switchId + " .commentaire");  // Only fiel form > to disable it
    let switchElements = $("." + switchId);  // Field + text > to show/hide when printing
    let switcher = $("#" + switchId);  // Accordion switch > to toggle
    console.log(switcher);
    console.log(switcher.hasClass("accordionSwitch"));
    console.log(switcher[0].checked);
    if ((switcher.hasClass("accordionSwitch") && !switcher[0].checked)  // If accordion, switcher is not automatically switched
             || (!switcher.hasClass("accordionSwitch") && switcher[0].checked)) {  // Input check already switch before call toggleSection()
        console.log("on");
        switchFields.removeAttr("disabled");
        switchElements.removeClass("d-print-none");
        switcher.attr("checked", true);
    } else {
        console.log("off");
        switchFields.attr("disabled", "disabled");  // To not print them in PDF
        switchElements.addClass("d-print-none");
        switcher.removeAttr("checked");
    }

    updateCommentSection(devisId);
}


/**
 * Create a switch element
 * @param  devisId          To which devis does it treat, to update comment section
 * @return sectionSwitcher  The switcher in DOM element
 */
function createSwitchElement(devisId, classes="ps-0") {
    let sectionSwitcher = document.createElement("div");
    sectionSwitcher.className = classes + " mx-0 form-check-inline form-switch d-print-none d-none";

    if (!simpleProfil) {
        sectionSwitcher.className += " d-sm-inline";
    }

    let switcher = document.createElement("input");
    switcher.className = "form-check-input switch";
    switcher.id = "switch" + switchCounter;
    switchCounter += 1;
    switcher.setAttribute("onClick", "toggleSection('" + devisId + "', this.id)");
    switcher.setAttribute("type", "checkbox");
    switcher.setAttribute("role", "switch");
    switcher.setAttribute("checked", true);
    sectionSwitcher.appendChild(switcher);
    return sectionSwitcher;
}

function populateAccordion(accordion, sectionData, sectionName, accordionId, devisId) {
    if (sectionName == 'commentaires') {
        return;
    }

    collapsed = sectionName.startsWith("#")
    if (collapsed) {
        sectionName = sectionName.slice(1);  // Remove #
    }

    // To have only on check/label id even with several devis
    sectionPrefix = devisId + "_" + sectionName.replaceAll(" ", "-") + "_";

    let sectionSwitcher = createSwitchElement(devisId, "");
    sectionSwitcher.firstChild.className += " accordionSwitch d-none";
    sectionSwitcher.firstChild.disabled = true
    switchId = sectionSwitcher.firstChild.id;

    accordionItemId = "accordion" + accordionItemCounter;
    accordionItemCounter += 1;
    let accordionItem = document.createElement("div");
    let accordionH5 = document.createElement("h4");
    accordionH5.className = "accordion-header";
    accordionH5.setAttribute("onClick",  "toggleSection('" + devisId + "', '" + switchId + "')");
    accordionH5.id = accordionItemId + "Header";
    let accordionBtn = document.createElement("h5");
    accordionBtn.setAttribute("data-bs-toggle", "collapse");
    accordionBtn.setAttribute("type", "button");
    accordionBtn.setAttribute("data-bs-target", "#" + accordionItemId);
    accordionBtn.setAttribute("aria-controls", accordionItemId);

    let accordionCollapse= document.createElement("div");
    accordionCollapse.id = accordionItemId;
    accordionCollapse.setAttribute("aria-labelledby", accordionItemId + "Header");
    accordionCollapse.setAttribute("data-bs-parent", accordionId);

    let accordionBody= document.createElement("div");

    if (collapsed) {
        sectionSwitcher.firstChild.removeAttribute("checked");
        accordionBtn.className = "accordion-button collapsed";
        accordionBtn.setAttribute("aria-expanded", false);
        accordionCollapse.className = "accordion-collapse collapse";
        accordionItem.className = "accordion-item d-print-none field " + switchId;
        accordionBody.className = "accordion-body d-print-none " + switchId;
    }
    else {
        accordionBtn.className = "accordion-button";
        accordionBtn.setAttribute("aria-expanded", true);
        accordionCollapse.className = "accordion-collapse collapse show";
        accordionItem.className = "accordion-item field " + switchId;
        accordionBody.className = "accordion-body " + switchId;
    }

    accordion.appendChild(accordionItem);
    accordionItem.appendChild(accordionH5);
    accordionItem.appendChild(accordionCollapse);
    accordionBtn.innerHTML = sectionName + sectionSwitcher.outerHTML;
    accordionH5.appendChild(accordionBtn);
    accordionCollapse.appendChild(accordionBody);


    // Add each checker
    for (i = 0; i < sectionData.length; i++) {
        if (typeof(sectionData[i]) =="object" && "info" in sectionData[i]) {
            sectionHelper = createHelperElement(sectionData[i]["info"]);
            accordionBtn.innerHTML += sectionHelper.outerHTML;
            continue;
        }

        populateCheck(accordionBody, sectionData[i], sectionName, devisId, sectionPrefix + i);
    }
}

function populateCheckSection(devisContent, sectionData, sectionName, devisId) {
    if (sectionName == 'commentaires' || sectionName.startsWith("#")) {
        return;
    }
    // To have only on check/label id even with several devis
    sectionPrefix = devisId + "_" + sectionName.replaceAll(" ", "-") + "_";


    let sectionSwitcher = createSwitchElement(devisId);
    switchId = sectionSwitcher.firstChild.id;

    // Add a switch to toggle or not the section
    let sectionTitle = document.createElement("h5");
    sectionTitle.className = sectionPrefix + " " + switchId + " ms-sm-4";
    sectionTitle.innerHTML = sectionSwitcher.outerHTML + sectionName;
    devisContent.appendChild(sectionTitle);


    // Add a fieldSet section to gather all checker and then be able to toggle the entire fieldSet with a switch
    let checkSection = document.createElement("fieldset");
    checkSection.className = "form-check px-2 ms-sm-4 " + switchId + " field";
    devisContent.appendChild(checkSection);

    // Add each checker
    for (i = 0; i < sectionData.length; i++) {
        if (typeof(sectionData[i]) =="object" && "info" in sectionData[i]) {
            sectionHelper = createHelperElement(sectionData[i]["info"]);
            sectionTitle.innerHTML += sectionHelper.outerHTML;
            continue;
        }

        populateCheck(checkSection, sectionData[i], sectionName, devisId, sectionPrefix + i);
    }
}

function populateCheck(checkSection, checkData, sectionName, devisId, checkPrefix) {
    // Create check switch first to get checkId, used to toggle the row
    let checkSwitcher = createSwitchElement(devisId, checkPrefix + " px-0");
    checkId = checkSwitcher.firstChild.id;

    // Create new row with 3 columns: switch  |  [] constraint  |  comments
    let checkRow = document.createElement("div");
    checkRow.className = "row " + checkId;
    checkSection.appendChild(checkRow);
    let col0 = document.createElement("div");
    let col1 = document.createElement("div");
    let col2 = document.createElement("div");
    col0.className = "col-auto mx-0 d-print-none form-check d-none d-sm-inline";
    col1.className = "col form-check";
    checkRow.appendChild(col0);
    checkRow.appendChild(col1);
    checkRow.appendChild(col2);

    // Add switch
    col0.appendChild(checkSwitcher);

    // Add checkbox
    let checkbox = document.createElement("input")
    checkbox.className = "form-check-input field " + checkId;
    checkbox.type = "checkbox";
    checkbox.id = checkPrefix;
    col1.appendChild(checkbox);

    // Add label
    let label = document.createElement("label")
    label.className = "form-check-label";
    label.setAttribute("for", checkPrefix);
    label.innerHTML = formatText(checkData);
    col1.appendChild(label)

    // Add helper
    if (typeof checkData === 'object') {
        addHelper(checkData, col1);
    }

    // Add a comment input for this check
    if (!simpleProfil) {
        col2.className = "col-sm-3 form-check d-none d-sm-block d-print-none";
        let comment = document.createElement("input");
        comment.className = "form-control ms-sm-3 commentaire " + devisId + "_checkComment field " + checkId;
        comment.id = checkPrefix + "_comment";
        comment.setAttribute('section', sectionName);
        comment.setAttribute('onblur', "updateCommentSection('" + devisId + "')");
        col2.appendChild(comment);
    }
}


function populateCommentsSection(devisContent, postData, devisId) {
    var space = document.createElement("div");
    space.className = "py-2";
    space.innerHTML = "&nbsp;";
    devisContent.appendChild(space);
    var sectionTitle = document.createElement("h5");
    sectionTitle.innerHTML = "Commentaires";
    devisContent.appendChild(sectionTitle);

    // To have only unique id even with several devis
    checkerPrefix = devisId + "_commentaires"

    var textarea = document.createElement("textarea");
    textarea.className = "form-control noPrintDisplay";
    if (simpleProfil) {
        textarea.className += " d-none"
    }
    textarea.id = checkerPrefix + "_textarea"
    textarea.setAttribute("rows", "5");
    textarea.setAttribute('onblur', "noPrintDisplayIfEmpty(" + checkerPrefix + "_textarea);");
    textarea.setAttribute('oninput', "auto_grow(this)");
    devisContent.appendChild(textarea);

    var commentsByCheck = document.createElement("div");
    commentsByCheck.id = checkerPrefix + "_by_check"
    commentsByCheck.className = "d-none d-print-block";
    devisContent.appendChild(commentsByCheck);

    if ('commentaires' in postData) {
        for (i = 0; i < postData['commentaires'].length; i++) {
            var checkDiv = document.createElement("div");

            // Add label (text for checkbox)
            var label = document.createElement("label")
            checkerLabel = postData['commentaires'][i];
            // if URL, make it clickable
            label.innerHTML = formatText(checkerLabel);
            checkDiv.appendChild(label)

            devisContent.appendChild(checkDiv);
        }
    }
}

function auto_grow(element) {
    element.style.height = "1px";
    element.style.height = (25+element.scrollHeight)+"px";
}

function formatText(text) {
    // If there is an helper for this checker, parse it
    var checkerLabel = text;
    if (typeof text === 'object') {
        for(const key in text) {
            checkerLabel = key;
            break;
        }
    }

    // If label is an URL, make it clickable
    checkerLabel = formatURL(checkerLabel);

    return checkerLabel;
}

function formatURL(text) {
    text = text.replaceAll(/\[([^\]]+)\]\(([^\)]+)\)/g, '<a href="$2" target="_blank">$1</a>');
    text = text.replaceAll(/\n/g, "<br />");
    return text;
}

function resetForm() {
    let okToReset = confirm("Attention, la fonction 'reset' efface tous les devis en cours.\n\nPour n'effacer qu'un devis, fermez l'onglet de ce devis et rajoutez de nouveau le poste concerné.\n\n Pressez sur 'OK' pour continuer, ou sur 'Cancel' (ou la touche échap) pour annuler.");
    if (okToReset) {
          window.location.reload();
    }
}


// ===========================================================================
// Print section
// ===========================================================================
function addPrintButton(devisContent) {
    var button = document.createElement("a");
    button.className = "btn btn-primary float-end mb-5 d-none d-sm-block";
    button.innerHTML = "<i class=\"bi bi-printer\"></i>";
    button.setAttribute("role", "0");
    button.setAttribute("tabindex", "button");
    button.setAttribute("onClick", "printReport()");
    button.setAttribute("aria-controls", "imprimer");

    devisContent.appendChild(button);
}

function printReport() {
    addEventToStats("impression");
    addEventToStats("impression_"+profil);
    let previousTitle = document.title;
    let date = $("#current-date")[0].placeholder.replaceAll("/", "-");
    let particulier = $("#particulier")[0].value  || "nom-particulier-non-renseigné";
    particulier.replace(" ", "-");
    document.title = particulier  + "_Vérification-devis-" + profil + "_" + date;
    print();
    document.title = previousTitle;
}

// This function merge all check comments into a comments section at the end of devis page (displayed only for print)
function updateCommentSection(devisId) {
    currentSection = "";
    commentsByCheckNode = document.getElementById(devisId + "_commentaires_by_check");
    commentsByCheckNode.innerHTML = "";
    currentUl = "";
    $("." + devisId + "_checkComment").each(function() {
        // If empty comment, go next
        if (!this.value) {
            return true;
        }

        // Do not print comment if section or checker are switched off
        if (this.parentNode.parentNode.parentNode.disabled || this.disabled) {
            return true;
        }

        // If new section for this loop, create the list in currentUl
        if (currentSection != this.getAttribute("section")) {
            var title = document.createElement("b");
            title.innerHTML = this.getAttribute("section");
            commentsByCheckNode.appendChild(title);
            var ul = document.createElement("ul");
            ul.className = "commentaire";
            commentsByCheckNode.appendChild(ul);

            currentSection = this.getAttribute("section");
            currentUl = ul;
        }

        var li = document.createElement("li");
        li.innerHTML = this.value;
        currentUl.appendChild(li);
    });
}

function noPrintDisplayIfEmpty(elemId, elemIdToNotPrint=null) {
    if (elemIdToNotPrint==null) {
        elemIdToNotPrint = elemId;
    }

    if (!$(elemId).val()) {  // If empty
        $(elemIdToNotPrint).addClass("noPrintDisplay");
    }
    else {  // If not empty
        $(elemIdToNotPrint).removeClass("noPrintDisplay");
    }
}


// ===========================================================================
// Helpers
// ===========================================================================
function createHelperElement(text) {
    let helper = document.createElement("a");
    helper.className = "btn-primary pop noPrintDisplay";
    helper.setAttribute("role", "button");
    helper.setAttribute("tabindex", "0");
    helper.setAttribute("data-bs-toggle", "popover");
    helper.setAttribute("data-bs-custom-class", "custom-popover");  // Class to stylish popover
    helper.setAttribute("data-bs-placement", "right");
    helper.setAttribute("data-bs-html", "true");
    helper.setAttribute("data-bs-content", formatURL(text));
    helper.innerHTML = '<i class="bi bi-question-circle" style="margin: 10px;"></i>';
    return helper;
}

function addHelper(checkData, checkDiv) {
    for(const key in checkData) {
        checkerHelp = checkData[key];
        break;
    }

    let helper = createHelperElement(checkerHelp)
    checkDiv.appendChild(helper);

    // Verify if annexe already exists for this help
    if (checkerHelp in helperAnnexes) {
        annexeId = helperAnnexes[checkerHelp];
    }
    else {  // If annexe not exists, create it
        helperAnnexesCounter += 1;
        annexeId = helperAnnexesCounter;
        helperAnnexes[checkerHelp] = annexeId;
        var annexes = document.getElementById("annexes");
        var annexe = document.createElement("p");
        annexes.appendChild(annexe);
        annexe.className = "annexe";
        annexe.id = "helper_" + annexeId;
        annexe.innerHTML = "[" + annexeId + "]   " + formatURL(checkerHelp)
    };

    // Add ref to annexes
    var printHelper = document.createElement("span")
    printHelper.className = "d-none d-print-inline ms-2";
    printHelper.setAttribute("tabindex", "0");
    printHelper.setAttribute("helper", annexeId);
    printHelper.innerHTML = "<span class=\"annexe\">[" + annexeId + "]</span>"
    checkDiv.appendChild(printHelper);
}


function updateAnnexes() {
    console.log("updateAnnexes");
    // Delete all annexes that have no more anchor/reference to them
    for (key in helperAnnexes) {
        let annexeId = helperAnnexes[key];
        let nReferences = document.querySelectorAll('[helper="' + annexeId +'"]').length;
        console.log(annexeId + " > " + nReferences);

        // If there is no more reference to this annexe, remove the annexe
        if (nReferences == 0) {
            console.log("Remove helper_" + annexeId);
            document.getElementById("helper_" + annexeId).remove();
            delete helperAnnexes[key];
        }
    }

};


// ===========================================================================
// Monitoring and stats
// ===========================================================================
function addEventToStats(name) {
    name = name.replace("-", "_");
    $.ajax({
        contentType: "application/json",
        type: 'GET',
        dataType:"jsonp",
        url: './stats.php?event=' + name,
        success: function (data, status, xhr) {
            console.log('SUCCESS data: ', data);
        },
        error: function (data, status, xhr) {
            console.log('ERROR data: ', data);
            console.log('ERROR status: ', status);
            console.log('ERROR xhr: ', xhr);
        },
        dataType: "html"
    });
}
