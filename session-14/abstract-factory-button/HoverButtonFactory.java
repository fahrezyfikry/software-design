public class HoverButtonFactory implements ButtonFactory {
    @Override
    public Button createButton() {
        return new HoverButton();
    }
}