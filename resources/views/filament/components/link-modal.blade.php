<div class="p-4">
    <p class="break-all text-sm max-h-40 overflow-y-auto" id="link-text">{{ $link }}</p>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.copyLink = function() {
                const link = document.getElementById('link-text').textContent;
                navigator.clipboard.writeText(link).then(() => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'success', title: 'Link copied!' }
                    }));
                }).catch(err => {
                    console.error('Error copying link: ', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = link;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: { type: 'success', title: 'Link copied!' }
                        }));
                    } catch (err) {
                        console.error('Fallback copy failed: ', err);
                    }
                    document.body.removeChild(textArea);
                });
            };
        });
    </script>
</div>