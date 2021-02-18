// Particular element selection
const inputForm = document.getElementById('main-form');
const addSkillBtn = document.getElementById('add_skill');
const displaySkillsDiv = document.getElementById('display_skills');
const submitBtn = document.getElementById('submit_button');
const searchInput = document.getElementById('search-bar');

const cardFront = document.getElementById('card-front');
const cardBack = document.getElementById('card-back');
const swapToFront = document.getElementById('swapToFront');
const swapToBack = document.getElementById('swapToBack');

const mainTable = document.getElementById('main-table');
let tableBody = document.getElementById('table-body');
// const contentHolderDiv = document.getElementById('content-holder');

// User class for initializing a new user
class User {
  constructor({ name, email, skills }) {
    this.name = name;
    this.email = email;
    this.skills = skills;
  }
}

// UI class that deals with changes related the interface [additions, deletions, etc.]
class UI {
  populateTable(records) {
    if (records.length < 1) {
      alert('No records available: Create one to get started!');
    } else {
      records.forEach((record) => {
        console.log(record);
        const newTableRow = document.createElement('tr');
        newTableRow.id = record.email;

        let skills = '';

        record.skills.forEach((skill) => {
          skills += `<p id="${skill.id}">${skill.technology} | Level: ${skill.level}</p>`;
        });

        newTableRow.innerHTML = `
        <td>
          <p>${record.name}</p>
        </td>
        <td>
          <p>${record.email}</p>
        </td>
        <td>  
          <div id="skills">
            ${skills}
          </div>
        </td>
        <td>
          <i id="delete-record" class="fa fa-trash"></i>
        </td>`;

        tableBody.appendChild(newTableRow);
      });
    }
  }

  displaySearchRecords(records) {
    tableBody.innerHTML = '';
    records.forEach((record) => {
      const newTableRow = document.createElement('tr');
      newTableRow.id = record.email;

      let skills = '';

      record.skills.forEach((skill) => {
        skills += `<p id="${skill.id}">${skill.technology} | Level: ${skill.level}</p>`;
      });

      newTableRow.innerHTML = `
      <td>
        <p>${record.name}</p>
      </td>
      <td>
        <p>${record.email}</p>
      </td>
      <td>  
        <div id="skills">
          ${skills}
        </div>
      </td>
      <td>
        <i id="delete-record" class="fa fa-trash"></i>
      </td>`;

      tableBody.appendChild(newTableRow);
    });
  }

  removeRecord(record) {
    record.remove();
  }
}

// Store class that deals with the local storage
class Store {
  static getData() {
    let users;

    if (!localStorage.getItem('users')) {
      users = [];
    } else {
      users = JSON.parse(localStorage.getItem('users'));
    }

    return users;
  }

  static display() {
    const users = Store.getData();

    const ui = new UI();

    ui.populateTable(users);
  }

  static create(record) {
    const users = Store.getData();
    let flag = 1;

    users.forEach((user) => {
      if (user.email === record.email) {
        flag = 0;
      }
    });

    if (flag) {
      users.push(record);
      localStorage.setItem('users', JSON.stringify(users));
      swal({
        title: 'Welcome!',
        text: 'User added successfully!',
        icon: 'success',
        button: 'Close',
      });
      // alert('User added!');
      return true;
    } else {
      swal({
        title: 'Duplicate Record:',
        text: 'A user with that email already exists!',
        icon: 'error',
        button: 'Close',
      });
      // alert('A user with that email already exists!');
      return false;
    }
  }

  static delete(email) {
    let users = Store.getData();

    if (users.length < 1 || !email) {
      throw new Error("Invalid email - doesn't exist!");
    }

    users = users.filter((obj) => {
      return obj.email !== email;
    });

    localStorage.setItem('users', JSON.stringify(users));
  }
}

// Event Listeners: DOM-content-loaded, onSubmits, onClicks, etc.
window.addEventListener('DOMContentLoaded', function () {
  fetchTechnologies();
  Store.display();
});
searchInput.addEventListener(
  'keyup',
  debounce(function (e) {
    searchHandler(e);
  }, 350)
  // function (e) {
  //   searchHandler(e);
  // }
);
addSkillBtn.addEventListener('click', (e) => addSkillHandler(e));
inputForm.addEventListener('submit', (e) => submitFormHandler(e));
swapToFront.addEventListener('click', (e) => swapPageHandler(e));
swapToBack.addEventListener('click', (e) => swapPageHandler(e));
mainTable.addEventListener('click', (e) => deleteRecordHandler(e));

// Action-handler-functions & other Utilities
function fetchTechnologies() {
  const xhr = new XMLHttpRequest();
  const url = 'https://demo.stratbeans.com/atum-barium/index.php?r=site/fetchTechnologies';

  xhr.open('GET', url, true);

  xhr.onload = function () {
    if (this.status === 200) {
      const technologies = JSON.parse(this.responseText);

      const technologySelectDiv = document.getElementById('technology-drop');

      let output = '<option selected value="none">Select Technology</option>';

      technologies.forEach((technology) => {
        output += `<option value="${technology.id}">${technology.name}</option>`;
      });

      output = `<select class="form-select w-100 p-2" id="technology" name="technology">${output}</select>`;

      technologySelectDiv.innerHTML = output;
    } else {
      swal({
        title: 'Network Error:',
        text: `Failed to make a network request to URL: ${url} - kindly try again!`,
        icon: 'error',
        button: 'Close',
      });
    }
  };

  xhr.send();
}

function searchHandler(e) {
  //'keyup'
  if (e.target.value) {
    console.log('ran');
    const search = e.target.value.toLowerCase();

    const users = Store.getData();

    const filteredUsers = users.filter((user) => {
      const userName = user.name.toLowerCase().includes(search);
      const email = user.email.toLowerCase().includes(search);
      const skills = user.skills.filter(
        (el) => el.technology.toLowerCase().includes(search) || el.level.includes(search)
      )[0];
      return userName || email || skills;
    });

    const ui = new UI();
    ui.displaySearchRecords(filteredUsers);

    e.preventDefault();
  }
}

const addSkillHandler = (e) => {
  e.preventDefault();

  const technologyDD = document.getElementById('technology');
  const levelDD = document.getElementById('level');

  // Check if the both dropdown fields are selected or not
  if (technologyDD.value === 'none' || levelDD.value === 'none') {
    swal({
      title: 'Missing Fields:',
      text: 'Please select a valid technology and a valid skill level to go with it!',
      icon: 'warning',
      button: 'Close',
    });
  } else {
    if (submitBtn.disabled) {
      submitBtn.disabled = false;
      submitBtn.title = '';
    }

    // Check if no previous element exists in the div with the same value
    if (displaySkillsDiv.innerHTML.indexOf(`id="${technologyDD.value}"`) == -1) {
      const newElement = document.createElement('div');
      newElement.id = technologyDD.value;
      newElement.classList.add('displayBox');

      const heading = document.createElement('span');
      heading.innerHTML = technologyDD.options[technologyDD.selectedIndex].text;
      heading.innerHTML += ': Level ';

      newElement.appendChild(heading);

      const skillLevel = document.createElement('span');
      skillLevel.innerHTML = levelDD.value;

      newElement.appendChild(skillLevel);
      displaySkillsDiv.appendChild(newElement);
    } else {
      swal({
        title: 'Duplicate Record:',
        text: 'A technology with the same name is already present!',
        icon: 'error',
        button: 'Close',
      });
    }
  }
};

const submitFormHandler = (e) => {
  let target = e.target;

  const neededElements = ['name', 'email'];
  const skills = [];
  const formObj = {};

  for (let skill of displaySkillsDiv.children) {
    let skillObj = {
      id: skill.id,
      technology: skill.children[0].innerHTML.split(':')[0],
      level: skill.children[1].innerHTML,
    };

    skills.push(skillObj);
  }

  for (let i of neededElements) {
    const targetElement = target.elements[i];

    formObj[i] = targetElement.value;
  }

  formObj['skills'] = skills;

  const user = new User(formObj);

  const isUserCreated = Store.create(user);

  if (isUserCreated) {
    const ui = new UI();
    ui.populateTable([user]);
    clearFields(inputForm);
  }

  e.preventDefault();
};

const clearFields = (target) => {
  displaySkillsDiv.innerHTML = '';
  submitBtn.disabled = true;
  target.reset();
};

const swapPageHandler = (e) => {
  switch (cardFront.style.display) {
    case 'none':
      cardFront.style.display = 'block';
      cardBack.style.display = 'none';
      cardFront.style.opacity = 1;
      cardBack.style.opacity = 0;

      break;

    default:
      cardFront.style.display = 'none';
      cardBack.style.display = 'block';
      cardFront.style.opacity = 0;
      cardBack.style.opacity = 1;
  }

  clearFields(inputForm);

  e.preventDefault();
};

const deleteRecordHandler = (e) => {
  const target = e.target;

  if (target.id === 'delete-record') {
    const recToDelete = target.parentElement.parentElement;
    swal({
      title: 'Record Deleted:',
      text: `The record with e-mail id: ${recToDelete.id} has been deleted successfully!`,
      icon: 'success',
      button: 'Close',
    });
    const ui = new UI();
    ui.removeRecord(recToDelete);
    Store.delete(recToDelete.id);
  }

  e.preventDefault();
};

function debounce(fn, d) {
  let timer;

  return function () {
    let context = this,
      args = arguments;

    clearTimeout(timer);

    timer = setTimeout(() => {
      fn.apply(context, args);
    }, d);
  };
}
