// Particular element selection
const inputForm = document.getElementById('main-form');
const addSkillBtn = document.getElementById('add_skill');
const displaySkillsDiv = document.getElementById('display_skills');
const submitBtn = document.getElementById('submit_button');

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
  // UI class
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
      alert('User added!');
      return true;
    } else {
      alert('A user with that email already exists!');
      return false;
    }
  }
}

// Event Listener: onClick Handler - Adds a new technology-skill + technology-level to the output
addSkillBtn.addEventListener('click', (e) => addSkillHandler(e));
inputForm.addEventListener('submit', (e) => submitFormHandler(e));

// Action-handlers & other Utilities
const addSkillHandler = (e) => {
  e.preventDefault();

  const technologyDD = document.getElementById('technology');
  const levelDD = document.getElementById('level');

  // Check if the both dropdown fields are selected or not
  if (technologyDD.value === 'none' || levelDD.value === 'none') {
    alert('Please select a valid technology and a valid skill level to go with it!');
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
      alert('Duplication-Record-Error: Same technology already present!');
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
    clearFields(inputForm);
  }

  e.preventDefault();
};

const clearFields = (target) => {
  displaySkillsDiv.innerHTML = '';
  target.reset();
};
