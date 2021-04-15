"use strict"

document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('form');
	form.addEventListener('submit', formSend);

	async function formSend(e) {//Функция отправка формы
		e.preventDefault();

		let error = formValidate(form);//Запуск функции валидации которая возврщает ошибки

		let formData = new FormData(form);//Пакуем все данные формы в удобный формат
		// formData.append('image', formImage.files[0]);
		// formData.append('summary', formImage.files[1]);
		// console.log(formData.getAll('name'));
		if (error === 0) {
			form.classList.add('_sending');
			let response = await fetch('http://site2.com:8080/form.php', {
				method: 'POST',
				body: formData
			});
			if (response.ok) {
				alert('Exelent');
				//formPreview.innerHTML = '';
				//form.reset();
				form.classList.remove('_sending');
			} else {
				alert("Ошибка");
				form.classList.remove('_sending');
			}
		} else {
			alert('Заполните обязательные поля');
		}

	}


	function formValidate(form) {
		let error = 0;
		let formReq = document.querySelectorAll('._req');
		console.log(formReq);
		for (let index = 0; index < formReq.length; index++) {
			const input = formReq[index];
			formRemoveError(input);

			if (input.classList.contains('_email')) {
				if (emailTest(input)) {
					formAddError(input);
					error++;
				}
			}if (input.classList.contains('_tel')) {
				if (validatePhone(input)) {
					formAddError(input);
					error++;
				}
			}
			else {
				if (input.value === '') {
					formAddError(input);
					error++;
				}
			}
		}
		return error;
	}
	function formAddError(input) {
		input.parentElement.classList.add('_error');
		input.classList.add('_error');
	}
	function formRemoveError(input) {
		input.parentElement.classList.remove('_error');
		input.classList.remove('_error');
	}
	//Функция теста email
	function emailTest(input) {
		return !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/.test(input.value);
	}
	function validatePhone(input){
		return !/^\+380\d{3}\d{2}\d{2}\d{2}$/.test(input.value);

	}
	//Получаем инпут file в переменную
	const formImage = document.getElementById('formImage');
	//Получаем див для превью в переменную
	const formPreview = document.getElementById('formPreview');
	const formDock = document.getElementById('formSummary');
	const formPrev = document.getElementById('formPrev');
	//Слушаем изменения в инпуте file
	formImage.addEventListener('change', () => {
		uploadFile(formImage.files[0]);
	});
	formDock.addEventListener('change', () => {
		uploadFileResume(formDock.files[0]);
	});

	function uploadFile(file) {//Валидация загрузки файлов
		// провераяем тип файла

		if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
			alert('Разрешены только изображения.');
			formImage.value = '';
			return;
		}
		// проверим размер файла (<2 Мб)
		if (file.size > 2 * 1024 * 1024) {
			alert('Файл должен быть менее 2 МБ.');
			return;
		}

		var reader = new FileReader();
		reader.onload = function (e) {
			formPreview.innerHTML = `<img src="${e.target.result}" alt="Фото">`;
		};
		reader.onerror = function (e) {
			alert('Ошибка');
		};
		reader.readAsDataURL(file);
	}
	function uploadFileResume(file) {//Валидация загрузки файлов
		// провераяем тип файла
		console.log(file.type);
		if (!['application/msword', 'application/pdf'].includes(file.type)) {
			alert('Разрешены только документы: doc, pdf форматов.');
			formImage.value = '';
			return;
		}
		// проверим размер файла (<2 Мб)
		if (file.size > 2 * 1024 * 1024) {
			alert('Файл должен быть менее 2 МБ.');
			return;
		}

		var reader = new FileReader();
		reader.fileName = file.name // file came from a input file element. file = el.files[0];
		reader.onload = function(readerEvt) {
			console.log(readerEvt.target.fileName);
		};
		reader.onload = function (e) {
			console.log(e.target);
			formPrev.innerHTML = `${e.target.fileName}`;
		};
		reader.onerror = function (e) {
			alert('Ошибка');
		};
		reader.readAsDataURL(file);
	}
});