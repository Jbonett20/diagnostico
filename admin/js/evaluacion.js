import Crud from "../helpers/js/Crud.js";
import { SweeAlert } from "../helpers/js/SweeAlert.js";
import { SearchParamUrl } from "../helpers/js/SearchParamUrl.js";


document.addEventListener("DOMContentLoaded", function (e) {
    getEvaluacion();
    addMouseLeaveListener();
});

let questions = [];
let num =1;
let currentQuestionIndex = 0;

const getEvaluacion = () => {
    const getSearchUrl = new SearchParamUrl();
    const evaluacionid = getSearchUrl.buscar('eid');
    const categoriaid = getSearchUrl.buscar('cid');

    try {
        const crud = new Crud(`controllers/evaluacion.php?opcn=getEvaluacion&eid=${evaluacionid}&cid=${categoriaid}`);
        crud.listar((res) => {
            const { error, message, data } = res;
            if (!error) {
                data.forEach((element, i) => {
                    const options = element.options.map(opt => ({
                        text: opt.opc,
                        value: opt.value,
                        respuestaid:opt.respuestaid
                    }));
                    questions.push({
                        preguntaid: element.preguntaid,
                        items: element.items,
                        actividadid: element.actividadid,
                        actividad: element.actividad,
                        question: element.question,
                        options: options,
                        type: element.type
                    });
                });
                if (questions.length > 0) {
                    showQuestion(0);
                } else {
                    console.error("No se encontraron preguntas en los datos.");
                }
            } else {
                throw new Error(message);
            }
        });
    } catch (error) {
        console.log(`Error: ${error.message}`);
    }
};
function showQuestion(index) {
    const titulo = document.getElementById("tituloEvaluacion");
    const questionContainer = document.getElementById("question-container");
    questionContainer.innerHTML = "";

    if (questions && index < questions.length) {
        titulo.textContent = questions[index].actividad;
        titulo.style.fontSize='25px';
        titulo.style.color = '#000';
        titulo.style.backgroundColor = '#fff';
        document.getElementById("btn_next_question").style.display = "block";
        const question = questions[index];
        let htmlContent = `<div class="form-group">
                            <label>${num}) ${question.question}</label>`;
        if (question.type === "single") {
            question.options.forEach((option, i) => {
                htmlContent += `<div class="radio mb-1 parsley-success">
                                    <input type="radio" name="answer" id="option${i}" value="${option.value}" data-respuestaid="${option.respuestaid}" required="">
                                    <label for="option${i}">${option.text}</label>
                                </div>`;
            });
        } else if (question.type === "multiple") {
            htmlContent += `<p>Puedes marcar hasta ${question.items} respuesta(s).</p>`;
            question.options.forEach((option, i) => {
                htmlContent += `<div class="checkbox mb-1 checkbox-pink">
                                    <input type="checkbox" name="answer" id="option${i}" value="${option.value}" data-respuestaid="${option.respuestaid}" onclick="checkMaxSelections(this, ${question.items})">
                                    <label for="option${i}">${option.text}</label>
                                </div>`;
            });
        } else if (question.type === "text") {
            htmlContent += `<textarea class="form-control parsley-success" name="answer" rows="4" required=""></textarea>`;
        }

        htmlContent += `</div>`;
        questionContainer.innerHTML = htmlContent;
    } else {
        showFinalScore();
        document.getElementById("btn_next_question").style.display = "none";
    }
}


window.checkMaxSelections = function (checkbox, maxSelections) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="answer"]');
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

    if (checkedCount > maxSelections) {
        checkbox.checked = false;
        let icon = "error";
        let text = `Solo puedes seleccionar hasta ${maxSelections} opción(es).`;
        SweeAlert(icon, "info", text);
    }
}

function validateQuestion() {
    const question = questions[currentQuestionIndex];
    if (question.type === "single" || question.type === "multiple") {
        const selectedOptions = document.querySelectorAll('input[name="answer"]:checked');
        if (selectedOptions.length === 0) {
            let icon = "error";
            let text = "Debe seleccionar una opción.";
            SweeAlert(icon, "info", text);
            return false;
        }
    } else if (question.type === "text") {
        const textAnswer = document.querySelector('textarea[name="answer"]').value.trim();
        if (textAnswer === "") {
            let icon = "error";
            let text = "Debe escribir una respuesta.";
            SweeAlert(icon, "info", text);
            return false;
        }
    }
    return true;
}


document.getElementById("btn_next_question").addEventListener("click", (e) => {
    e.preventDefault();
    if (validateQuestion()) {
        GuardarScore();
    }
    num++;
});

function GuardarScore() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const preguntaActual = questions[currentQuestionIndex];
        const selectedOptions = document.querySelectorAll('input[name="answer"]:checked');

        const respuestas = Array.from(selectedOptions).map(option => ({
            texto: option.nextElementSibling.textContent.trim(),
            valor: parseFloat(option.value),
            respuestaid: option.getAttribute('data-respuestaid')
        }));
        const data = {
            eventoid: urlParams.get('eventoid'),
            actividadid: preguntaActual.actividadid,
            preguntaid: preguntaActual.preguntaid,
            categoriaid: urlParams.get('cid'),
            respuestas: respuestas
        };

        let crud = new Crud("controllers/evaluacion.php");
        crud.editar(data, (res) => {
            if (res.error) {
                SweeAlert("warning", "Error", res.message);
            } else {
                console.log(res.message);
                currentQuestionIndex++;
                if (currentQuestionIndex < questions.length) {
                    showQuestion(currentQuestionIndex);
                } else {
                    showFinalScore();
                }
            }
        });
    } catch (error) {
        console.log(error);
    }
}


function showFinalScore() {
    const questionContainer = document.getElementById("question-container");
    questionContainer.innerHTML = `<p>¡Esta evaluación ya fue realizada!   <i class="fa fa-check" aria-hidden="true"></i></p>`;
    const siguiente = document.getElementById('btn_next_question');
    siguiente.style.display='none';
}

// Mostrar la primera pregunta
showQuestion(currentQuestionIndex);
// Prevent page reload and navigation
window.addEventListener("beforeunload", function (e) {
    e.preventDefault();
    e.returnValue = "";
});

window.addEventListener("keydown", function (e) {
    if (e.key === "F5" || (e.ctrlKey && e.key === "r")) {
        e.preventDefault();
    }
});


function addMouseLeaveListener() {
    const examArea = document.querySelector('html'); 
    const siguiente = document.getElementById('btn_next_question');
    if (examArea) {
        examArea.addEventListener("mouseleave", function () {
            if (siguiente.style.display !== 'none') {
                SweeAlert("warning", "Aviso", "No puedes salir del área del examen, por favor vuelva al área del examen");
            }
        });
    } else {
        console.log("Examen terminado");
    }
}