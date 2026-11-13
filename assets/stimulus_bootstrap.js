import { startStimulusApp } from '@symfony/stimulus-bundle';

// Controllers in assets/controllers/ are registered automatically.
// To register a third-party controller, keep the returned app:
// const app = startStimulusApp(); app.register('name', Controller);
startStimulusApp();
