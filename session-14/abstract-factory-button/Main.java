// Client code
public class Main {
    public static void main(String[] args) {
        ButtonFactory hoverFactory = new HoverButtonFactory();
        Button hoverButton = hoverFactory.createButton();
        hoverButton.render();
        
        if (hoverButton instanceof HoverButton) {
            HoverButton hb = (HoverButton) hoverButton;
            hb.onMouseEnter();
            hb.onMouseLeave();
        }
        
        ButtonFactory clickFactory = new ClickButtonFactory();
        Button clickButton = clickFactory.createButton();
        clickButton.render();
        
        clickButton.onClick();
        clickButton.onClick();
    }
}