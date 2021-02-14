const addSkillBtn = document.getElementById('add_skill');
const displaySkillsDiv = document.getElementById('display_skills');
const submitBtn = document.getElementById('submit_button');

class User {
  constructor(name, email, skills, level) {
    this.name = name;
    this.email = email;
    this.skills = skills;
    this.level = level;
  }
}

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

    users.forEach((student) => {
      if (student.email === record.email) {
        flag = 0;
      }
    });

    if (flag) {
      users.push(record);
      localStorage.setItem('users', JSON.stringify(users));
      alert('Student record added!');
    } else {
      alert('A student with that email is already enrolled!');
    }
  }
}

// Event Listener: onClick Handler - Adds a new technology-skill + technology-level to the output
addSkillBtn.addEventListener('click', (e) => {
  e.preventDefault();

  const technologyDD = document.getElementById('technology');
  const levelDD = document.getElementById('level');

  // Check if the both dropdown fields are select or not
  if (technologyDD.value === 'none' || levelDD.value === 'none') {
    alert('Please select a valid technology and a valid skill level to go with it!');
  } else {
    if (submitBtn.disabled) {
      submitBtn.disabled = false;
      submitBtn.title = '';
    }

    // If no previous element exists in the div with the same value AND
    if (displaySkillsDiv.innerHTML.indexOf(`id="${technologyDD.value}"`) == -1) {
      // If both fields are selected: add it to the display box
      const newElement = document.createElement('div');
      newElement.id = technologyDD.value;
      newElement.classList.add('displayBox');

      const heading = document.createElement('span');
      heading.innerHTML = technologyDD.options[technologyDD.selectedIndex].text;
      heading.innerHTML += ' : Level ';

      newElement.appendChild(heading);

      const skillLevel = document.createElement('span');
      skillLevel.innerHTML = levelDD.value;

      newElement.appendChild(skillLevel);
      displaySkillsDiv.appendChild(newElement);
    } else {
      alert('Duplication-Record-Error: Same technology already present!');
    }
  }
});
