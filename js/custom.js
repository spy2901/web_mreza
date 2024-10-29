/*RESPOSNIVE NAVBAR PART USING JQUERY */
$(document).ready(function(){
    $('.hm-menu').click(function(){
        $('header').toggleClass('h-100');
        $('.hm-menu span').toggleClass('hm-100');
        $('html').toggleClass('over-x');
    });
     
     $('header nav a').click(function(){
        $('header').removeClass('h-100');
        $('.hm-menu span').removeClass('hm-100');
         $('html').removeClass('over-x');
    });
     
});


const targetWord = "gej";

// Select all elements with the class "post-description"
const postDescriptions = document.querySelectorAll('.post-description');
const postTitle = document.querySelectorAll('.post-title');

// Loop through each post description
postDescriptions.forEach(description => {
    // Check if the description contains the target word
    if (description.textContent.includes(targetWord)) {
        // Add a specific class if the word is found
        description.classList.add('something');
    }
});
postTitle.forEach(description => {
    // Check if the description contains the target word
    if (description.textContent.includes(targetWord)) {
        // Add a specific class if the word is found
        description.classList.add('something');
    }
});
const targetSentence = "nisam peder";
const targetRegex = new RegExp(targetSentence, 'i'); // 'i' for case-insensitive

postDescriptions.forEach(description => {
    if (targetRegex.test(description.textContent)) {
        description.classList.add('something');
    }
});

postTitle.forEach(title => {
    if (targetRegex.test(title.textContent)) {
        title.classList.add('something');
    }
});