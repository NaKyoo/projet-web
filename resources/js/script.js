document.addEventListener('DOMContentLoaded', function () {
    const studentButtons = document.querySelectorAll('[data-modal-toggle="#student-modal"]');
    const teacherButtons = document.querySelectorAll('[data-modal-toggle="#teacher-modal"]');
    const cohortButtons = document.querySelectorAll('[data-modal-toggle="#cohort-modal"]');


    studentButtons.forEach(function (studentButton) {
        studentButton.addEventListener('click', function () {
            const url = this.getAttribute('data-student');
            let modalBody   = document.querySelector('#student-modal .modal-body');

            modalBody.innerHTML = "Chargement...";

            // Requete Ajax GET avec l'URL récupéré
            fetch(url)
                .then(response=> {
                    return response.json();
                })
                .then(data => {
                    modalBody.innerHTML = data.html;
                })
        });
    });

    teacherButtons.forEach(function(teacherButton) {
       teacherButton.addEventListener('click', function() {
           const url = this.getAttribute('data-teacher');
           let modalBody   = document.querySelector('#teacher-modal .modal-body');

           modalBody.innerHTML = "Chargement...";

           // Requete Ajax GET avec l'URL récupéré
           fetch(url)
               .then(response=> {
                   return response.json();
               })
               .then(data => {
                   modalBody.innerHTML = data.html;
               })
       })
    });

    cohortButtons.forEach(function(cohortButton) {
        cohortButton.addEventListener('click', function() {
            const url = this.getAttribute('data-cohort');
            console.log(url);
            let modalBody   = document.querySelector('#cohort-modal .modal-body');

            modalBody.innerHTML = "Chargement...";

            // Requete Ajax GET avec l'URL récupéré
            fetch(url)
                .then(response=> {
                    return response.json();
                })
                .then(data => {
                    modalBody.innerHTML = data.html;
                })
        })
    });

});
