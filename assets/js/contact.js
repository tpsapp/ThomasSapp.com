/**
 * Contact Form & Copy Email Module
 * Handles accessible form validation, async PHP submission, anti-spam honeypot,
 * anti-bot cookie challenge resolution (HTTP 409), and copy-to-clipboard functionality.
 */

(function () {
  'use strict';

  function initCopyEmail() {
    const copyBtns = document.querySelectorAll('.copy-email-btn');
    copyBtns.forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const email = btn.getAttribute('data-email') || 'me@thomassapp.com';
        try {
          await navigator.clipboard.writeText(email);
          const origText = btn.innerHTML;
          btn.innerHTML = '✓ Copied!';
          btn.setAttribute('aria-label', 'Email address copied to clipboard');
          setTimeout(() => {
            btn.innerHTML = origText;
            btn.setAttribute('aria-label', 'Copy email address to clipboard');
          }, 2500);
        } catch (err) {
          window.location.href = `mailto:${email}`;
        }
      });
    });
  }

  function initContactForm() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    const feedbackBox = document.getElementById('form-feedback');
    const submitBtn = document.getElementById('form-submit-btn');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Check honeypot field
      const honeypot = form.elements['website'] ? form.elements['website'].value : '';
      if (honeypot) {
        // Bot detected, silently show success without sending
        showFeedback('Thank you! Your message has been received.', 'success');
        form.reset();
        return;
      }

      const name = form.elements['name'].value.trim();
      const email = form.elements['email'].value.trim();
      const subject = form.elements['subject'] ? form.elements['subject'].value.trim() : 'Portfolio Contact';
      const message = form.elements['message'].value.trim();

      if (!name || !email || !message) {
        showFeedback('Please fill out all required fields.', 'error');
        return;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showFeedback('Please enter a valid email address.', 'error');
        return;
      }

      // UI Loading state
      submitBtn.disabled = true;
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.innerHTML = 'Sending message...';
      hideFeedback();

      const formData = new FormData(form);

      try {
        let response = await fetch('contact.php', {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        // Intercept host anti-bot cookie challenge (HTTP 409 Conflict)
        if (response.status === 409) {
          const challengeHtml = await response.text();
          const match = challengeHtml.match(/document\.cookie\s*=\s*["']([^"']+)["']/);
          if (match && match[1]) {
            document.cookie = `${match[1]}; path=/; SameSite=Lax; Secure`;

            // Retry submission once with the newly assigned cookie in the browser jar
            response = await fetch('contact.php', {
              method: 'POST',
              body: formData,
              headers: {
                'Accept': 'application/json'
              }
            });
          }
        }

        if (response.ok) {
          const result = await response.json().catch(() => ({ success: true, message: 'Message sent!' }));
          if (result.success) {
            showFeedback(result.message || 'Thank you! Your message has been sent successfully.', 'success');
            form.reset();
          } else {
            showFeedback(result.message || 'There was an error sending your message. Please try emailing me@thomassapp.com directly.', 'error');
          }
        } else {
          throw new Error(`Server returned HTTP ${response.status}`);
        }
      } catch (err) {
        console.warn('Backend PHP endpoint unavailable. Opening email client fallback.', err);
        showFeedback('Unable to reach server directly. Opening your default email client...', 'success');
        const mailtoUrl = `mailto:me@thomassapp.com?subject=${encodeURIComponent(subject + ' - from ' + name)}&body=${encodeURIComponent(message + '\n\nFrom: ' + name + ' (' + email + ')')}`;
        setTimeout(() => {
          window.location.href = mailtoUrl;
        }, 800);
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });

    function showFeedback(msg, type) {
      if (!feedbackBox) return;
      feedbackBox.className = `form-feedback ${type}`;
      feedbackBox.textContent = msg;
      feedbackBox.setAttribute('role', 'status');
    }

    function hideFeedback() {
      if (!feedbackBox) return;
      feedbackBox.className = 'form-feedback';
      feedbackBox.textContent = '';
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    initCopyEmail();
    initContactForm();
  });
})();
